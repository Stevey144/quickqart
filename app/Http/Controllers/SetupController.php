<?php

namespace App\Http\Controllers;

use App\Category;
use App\Notifications\StoreSetupCompletedNotification;
use App\Product;
use App\ProductInventory;
use App\Role;
use App\Store;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SetupController extends Controller
{
    public function verifyStoreId(Request $request)
    {
        if (!in_array($this->subdomain, ['setup', 'store'])) {
            return $this->withMessage('Invalid request', 400);
        }

        $this->validate($request, [
            'store_id' => 'required'
        ]);

        $slug = strtolower($request->store_id);
        if (in_array($slug, ['www', 'control', 'cp', 'control-panel', 'abc', 'xyz', 'store', 'shop'])) {
            return $this->withMessage("Invalid ID", 400);
        }

        $storeId = Str::limit(Str::slug($request->store_id), 20, '');
        if (Store::query()->where(['slug' => $storeId])->first()) {
            return $this->withJson(['message' => 'This ID has been taken']);
        }

        return $this->withJson(['data' => $storeId]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->stores()->count() > 0) {
            return $this->withMessage("You have completed your setup process earlier.", 400);
        }
        $this->validate($request, [
            'slug' => 'required|unique:stores,slug',
            'name' => 'required',
            'description' => 'required',
            'contact' => 'nullable|array',
            'address' => 'nullable|array',
            'logo' => 'nullable',
            'mobile_logo' => 'nullable',
            'categories' => 'nullable|array',
            'categories.*.name' => 'string',
            'products' => 'nullable|array',
            'products.*.name' => 'required',
            'products.*.price' => 'required|min:0',
            'products.*.quantity' => 'required|numeric|min:0',
            'products.*.sku' => 'nullable',
            'products.*.description' => 'required',
            'products.*.category' => 'required',
            'products.*.properties' => 'array',
            'products.*.variants' => 'array',
            'products.*.images' => 'array',
            'currency' => 'array',
        ], [
            'slug.unique' => 'The selected Store ID has already been taken.'
        ]);

        $storeId = Str::limit(Str::slug($request->slug), 20, '');

        if (Store::query()->where(['slug' => $storeId])->first()) {
            return $this->withMessage('The specified store ID has been taken', 400);
        }

        $data = $request->only('name', 'description', 'logo', 'mobile_logo');
        $data['slug'] = $storeId;
        $data['user_id'] = auth()->id();
        $data['contact'] = json_encode($request->get('contact'));
        $data['address'] = json_encode($request->get('address'));
        $data['currency'] = json_encode($request->has('currency') ? $request->currency : [
            'rate' => 1,
            'code' => '₦',
            'name' => 'Naira',
            'symbol' => 'NGN',
        ]);

        $store = Store::query()->create($data);

        if (!$store) {
            return $this->withMessage('Unable to complete setup, please try again.', 500);
        }

        $categories = (array)$request->get('categories');

        if ($categories && count($categories)) {

            foreach ($categories as $category) {
                $slug = Str::limit(Str::slug(($category['name'])), 20, '') . '-' . time();
                Category::query()->updateOrCreate([
                    'name' => $category['name'],
                    'store' => $store->slug
                ], [
                    'slug' => $slug,
                    'property' => json_encode([]),
                ]);
            }
        }

        $products = (array)$request->get('products');
        foreach ($products as $product) {
            $category = $product['category'];
            if ($category) {
                $categoryObj = Category::query()->firstOrCreate(
                    ['name' => $category['name'], 'store' => $store->slug],
                    ['property' => json_encode($category['properties'])]
                );

                $slug = Str::limit(Str::slug(($product['name'])), 20, '') . '-' . time();
                $productObj = Product::query()->updateOrCreate([
                    'name' => $product['name'],
                    'store' => $store->slug
                ], [
                    'slug' => $slug,
                    'category_id' => $categoryObj->id,
                    'sku' => $product['sku'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'min_quantity' => 1,
                    'multiple' => 1,
                    'quantity' => $product['quantity'],
                    'unit' => 'unit',
                    'properties' => json_encode($product['properties']),
                    'variants' => json_encode($product['variants']),
                    'images' => json_encode($product['images'])
                ]);

                if ($productObj) {
                    ProductInventory::query()->create(['product_id' => $productObj->id, 'quantity' => $product['quantity'],
                        'type' => 'stock', 'store' => $store->slug]);
                }
            }
        }

        $token = Auth::guard()->refresh();
        $storeLink = "https://store.quickqart.com/auth/$token";;
        $storeLinkO = "https://" . $store->slug . ".quickqart.com";

        $productCount = Product::query()->where('store', $store->slug)->count();
        $store->update(['total_products' => $productCount]);

        try {
            $user = $store->user;
            $user->notify(new StoreSetupCompletedNotification($store, true));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        $users = User::query()->whereHas('roles', function ($query) {
            $query->where('name', Role::SYSADMIN);
        })->get();
        try {
            foreach ($users as $admin) {
                $admin->notify(new StoreSetupCompletedNotification($store, false));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $this->withJson([
            'message' => "Yay! Your store has been created successfully. Here's a link to you store.<br>$storeLinkO.<br>Please copy and share this link with your prospective customers.",
            'link' => $storeLink
        ]);

    }
}
