<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductInventory;
use Illuminate\Http\Request;

class ProductInventoryController extends Controller
{
    public function index($subdomain, $slugId)
    {
        $product = Product::query()->with('category')->owned()->where('slug', $slugId)->first();

        if (!$product) {
            return $this->withMessage('Product not found', 404);
        }

        $inventory = $product->inventory()->withDates()->get();

        return $this->withJson(['data' => $inventory]);
    }

    public function store(Request $request, $subdomain, $slugId)
    {
        $this->validate($request, [
            'quantity' => 'required'
        ]);

        $store = $this->getAuthUserStoreFromHeader();

        if (!$store) {
            return $this->withMessage('Store not found', 404);
        }

        $product = Product::query()->with('category')->owned()->where('slug', $slugId)->first();

        if (!$product) {
            return $this->withMessage('Product not found', 404);
        }

        $quantity = $request->quantity;

        $actionType = $request->has('action_type') ? $request->action_type :  'stock';

        if ($actionType != 'stock') {
            $actionType = 'bought';

            if ($product->quantity < $quantity) {
                return $this->withMessage("Available quantity is not up to $quantity", 400);
            }
        }

        $inventoryRecord = ProductInventory::query()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'type' => $actionType, 'store' => $store->slug
        ]);

        if ($inventoryRecord) {

            $product->quantity = $product->inventory()->stocks()->sum('quantity')
                + $product->inventory()->returned()->sum('quantity')
                - $product->inventory()->bought()->sum('quantity');

            $product->save();

            $message = "$quantity units of $product->name added to your stock.";

            if ($actionType != 'stock') {
                $message = "$quantity units of $product->name has been deducted as offline sales";
            }

            return $this->withJson([
                'data' => $inventoryRecord,
                'total_quantity' => $product->quantity,
                'message' => $message
            ]);
        } else {
            return $this->withMessage('Unable to complete, try again', 500);
        }
    }


}
