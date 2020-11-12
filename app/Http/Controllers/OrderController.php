<?php

namespace App\Http\Controllers;

use App\Notifications\NotifyUserOnOrderCancelled;
use App\Notifications\NotifyUserOnOrderItemRemoval;
use App\Notifications\OrderCheckedOutNotification;
use App\Order;
use App\OrderItem;
use App\Product;
use App\ProductInventory;
use App\Store;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter');
        $data = Order::query()->where(function ($query) use ($filter) {
            $query->where("contact", "like", "%$filter%");
        })->owned()->withDates()->latest()->paginate();

        $data->appends($request->all());

        return $this->withJson(['data' => $data]);
    }

    public function show($subdomain, $slug)
    {
        $order = Order::query()->where('slug', $slug)->with([
            'orderItems' => function ($query) {
                return $query->with('product');
            }
        ])->first();

        if ($order) {
            return $this->withJson(['data' => $order]);
        }

        return $this->withMessage("Order: $slug was not found", 404);
    }

    public function showForAdmin($subdomain, $slug)
    {
        $order = Order::query()->where('slug', $slug)->with([
            'orderItems' => function ($query) {
                return $query->with('product');
            }
        ])->owned()->first();

        if ($order) {
            return $this->withJson(['data' => $order]);
        }

        return $this->withMessage("Order: $slug was not found", 404);
    }

    public function updateQuantity(Request $request, $subdomain, $slug, $item_slug)
    {
        $this->validate($request, [
            'quantity' => 'required|min:1'
        ]);

        $quantity = $request->get('quantity');
        $orderItem = OrderItem::query()->where('slug', $item_slug)->with('order', 'product')
            ->whereHas('order', function ($query) use ($slug) {
                $query->where('slug', $slug)->owned();
            })->owned()->first();

        if (!$orderItem) {
            return $this->withMessage("Order item was not found", 404);
        }

        $order = $orderItem->order;

        if (!$order) {
            return $this->withMessage('Order not found', 404);
        }

        if (!$order->can_cancel) {
            return $this->withMessage('Item quantity cannot be updated for this order', 400);
        }

        $oldQuantity = $orderItem->quantity;

        if ($oldQuantity == $quantity) {
            return $this->withMessage("No changes made", 400);
        }

        $diff = $oldQuantity - $quantity;

        if ($diff < 0) { // adding more quantity
            if ($orderItem->product->quantity < $diff) {
                return $this->withMessage('You need a stock quantity of ' . abs($diff) . ' to complete.', 400);
            }
        }

        $type = 'restock';
        if ($diff < 0) {
            $type = 'bought';
        }

        ProductInventory::query()->create([
            'product_id' => $orderItem->product_id, 'quantity' => abs($diff), 'type' => $type, 'store' => $orderItem->store
        ]);

        $orderItem->update([
            'quantity' => $quantity,
            'amount' => $orderItem->product->price * $quantity,
            'unit_price' => $orderItem->product->price
        ]);

        $orderItem->product->update([
            'quantity' => $orderItem->product->quantity + $diff
        ]);

        return $this->showForAdmin($subdomain, $slug);
    }

    public function removeItem($subdomain, $slug, $item_slug)
    {
        $orderItem = OrderItem::query()->where('slug', $item_slug)->with('order', 'product')
            ->whereHas('order', function ($query) use ($slug) {
                $query->where('slug', $slug)->owned();
            })->owned()->first();

        if (!$orderItem) {
            return $this->withMessage("Order item was not found", 404);
        }

        $order = $orderItem->order;

        if (!$order) {
            return $this->withMessage('Order not found', 404);
        }

        if (!$order->can_cancel) {
            return $this->withMessage('Item cannot be removed for this order', 400);
        }

        $inventory = ProductInventory::query()->create([
            'product_id' => $orderItem->product_id, 'quantity' => $orderItem->quantity,
            'type' => 'restock', 'store' => $orderItem->store
        ]);

        if ($orderItem->delete()) {
            $store = Store::query()->with('user')->where('slug', $orderItem->store)->first();
            if ($store && $store->user) {
                try {
                    $store->user->notify(new NotifyUserOnOrderItemRemoval($orderItem));
                    (new User([
                        'email' => $orderItem->order->contact['email']
                    ]))->notify(new NotifyUserOnOrderItemRemoval($orderItem));
                } catch (\Exception $ex) {
                }
            }
            return $this->showForAdmin($subdomain, $slug);
        }

        $inventory->delete();

        return $this->withMessage('Unable to complete, try again.', 500);
    }

    public function destroy($subdomain, $slug)
    {
        $order = Order::query()->with('orderItems')->where('slug', $slug)->owned()->first();

        if (!$order) {
            return $this->withMessage("Order was not found", 404);
        }

        if (!$order->can_cancel) {
            return $this->withMessage('Order cannot be cancelled', 400);
        }

        foreach ($order->orderItems as $item) {
            ProductInventory::query()->create([
                'product_id' => $item->product_id, 'quantity' => $item->quantity, 'type' => 'restock', 'store' => $item->store
            ]);
        }

        if ($order->update(['status' => 'cancelled'])) {
            $store = Store::query()->with('user')->where('slug', $order->store)->first();
            if ($store && $store->user) {
                try {
                    $store->user->notify(new NotifyUserOnOrderCancelled($order));
                    (new User([
                        'email' => $order->contact['email']
                    ]))->notify(new NotifyUserOnOrderCancelled($order));
                } catch (\Exception $ex) {
                }
            }
            return $this->show($subdomain, $slug);
        }

        return $this->withMessage('Unable to complete, try again.', 500);
    }

    public function updateStatus(Request $request, $subdomain, $slug)
    {
        $this->validate($request, [
            'status' => 'required'
        ]);

        $order = Order::query()->with('orderItems')->where('slug', $slug)->owned()->first();

        if (!$order) {
            return $this->withMessage("Order was not found", 404);
        }

        $status = $request->get('status');

        if (!in_array($status, ['paid', 'delivered']) || ($status == 'paid' && !$order->can_pay) ||
            ($status == 'delivered' && !$order->can_be_delivered)) {
            return $this->withMessage('Unable to complete, refresh and try again.', 400);
        }

        if ($order->update(['status' => $status])) {
//            $store = Store::query()->with('user')->where('slug', $order->store)->first();
//            if ($store && $store->user) {
//                try {
//                    $store->user->notify(new NotifyUserOnOrderCancelled($order));
//                    (new User([
//                        'email' => $order->contact['email']
//                    ]))->notify(new NotifyUserOnOrderCancelled($order));
//                } catch(\Exception $ex) {}
//            }
            return $this->show($subdomain, $slug);
        }

        return $this->withMessage('Unable to complete, try again.', 500);
    }

    public function checkout(Request $request, $subdomain)
    {
        $store = Store::query()->with('user')->where('slug', $this->subdomain)->first();

        if (!$store) {
            return $this->withMessage('Store not found', 404);
        }

        $this->validate($request, [
            'items' => 'required|array',
            'items.*.product' => 'required|exists:products,slug',
            'items.*.quantity' => 'required|min:1',
            'items.*.options' => 'nullable|array',
            'contact' => 'required|array',
            'contact.firstName' => 'required',
            'contact.lastName' => 'nullable',
            'contact.phone' => 'required',
            'contact.email' => 'nullable',
            'contact.city' => 'required',
            'contact.state' => 'required',
            'contact.country' => 'required',
            'contact.address1' => 'required',
            'contact.address2' => 'nullable',
            'contact.postCode' => 'nullable',
            'contact.comment' => 'nullable',
        ]);

        $items = $request->get('items');
        $contact = $request->get('contact');

        foreach (['email', 'lastName', 'postCode'] as $field) {
            if (!isset($contact[$field])) {
                $contact[$field] = '';
            }
        }

        $missing = [];
        $willBuy = [];
        $notEnough = [];

        foreach ($items as $item) {
            $product = Product::query()->where('slug', $item['product'])->first();

            if ($product) {
                if ($product->quantity < $item['quantity']) {
                    $notEnough[] = "$product->name: $product->quantity";
                } else {
                    $willBuy[] = [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'options' => isset($item['options']) ? $item['options'] : []
                    ];
                }
            } else {
                $missing[] = $item['product'];
            }
        }

        if (count($missing)) {
            return $this->withMessage("The following goods are not available", 404);
        }

        if (count($notEnough)) {
            $notEnough = array_map(function ($value) {
                return "<li>$value</li>";
            }, $notEnough);
            return $this->withMessage("The quantity available for the following goods are:<br><uL>" . implode("<br>", $notEnough) . "</uL>", 404);
        }

        $order = Order::query()->create([
            'slug' => $this->generateSlug($subdomain),
            'contact' => json_encode($contact),
            'status' => 'checkout',
            'store' => $this->subdomain
        ]);

        foreach ($willBuy as $item) {
            $key = $item['product']->id;
            $quantity = $item['quantity'];
            $orderItem = OrderItem::query()->create([
                'order_id' => $order->id,
                'product_id' => $key,
                'options' => isset($item['options']) ? json_encode($item['options']) : null,
                'slug' => Str::uuid(),
                'store' => $store->slug,
                'quantity' => $quantity,
                'unit_price' => $item['product']->price,
                'amount' => $item['product']->price * $item['quantity']
            ]);

            ProductInventory::query()->create([
                'product_id' => $key, 'quantity' => $quantity, 'type' => 'bought', 'store' => $store->slug
            ]);

            $product = $orderItem->product;

            if ($product) {
                $availableQuantity = $product->quantity - $quantity;
                $product->update([
                    'quantity' => $availableQuantity
                ]);
            }
        }

        $order->load([
            'orderItems' => function ($query) {
                $query->with('product');
            }
        ]);

        if ($store) {
            try {
                $store->user->notify(new OrderCheckedOutNotification($store, $order));
            } catch (\Exception $c) {
                Log::alert($c->getMessage());
            }
            try {
                if ($order->contact['email']) {
                    (new User(['email' => $order->contact['email']]))->notify(new OrderCheckedOutNotification($store, $order, false));
                }
            } catch (\Exception $c) {
                Log::alert($c->getMessage());
            }

        }

        return $this->withJson([
            'data' => $order,
            'message' => 'Your order has been placed successfully',
            'extra' => [
                'store' => $store
            ]
        ]);
    }

    public function generateSlug($store)
    {
//        $slugArray = [];

//        foreach (range(0, 6) as $range) {
//            $slugArray[] = Str::random(6);
//        }

        $slug = rand(100000, 999999);

        if (DB::table('orders')->where('store', $store)->where('slug', $slug)->count()) {
            return $this->generateSlug($store);
        }

        return $slug;
    }
}
