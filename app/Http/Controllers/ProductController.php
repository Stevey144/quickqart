<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function publicIndex(Request $request)
    {
        $filter = $request->input('filter');
        $category = $request->input('category');
        $order = $request->input('sort');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        if ($order != 'desc') {
            $order = 'asc';
        }

        $data = Product::query()->with('category', 'reviews')->available()
            ->where(function ($query) use ($category) {
                if ($category) {
                    $query->whereHas('category', function ($q) use ($category) {
                        $q->where( 'slug', $category);
                    });
                }
            })->where(function ($query) use ($minPrice, $maxPrice) {
                if ($minPrice) {
                    $query->where('price', '>=', $minPrice);
                }
                if ($maxPrice) {
                    $query->where('price', '<=', $maxPrice);
                }
            })->where(function ($query) use ($filter) {
                $query->where('name', "like", "%$filter%")->orWhere('description', 'like', "%$filter%")->with('category');
            })->orderBy('is_featured')->orderBy('name', $order)->paginate();

        $data->appends($request->all());

        return $data;
    }

    public function publicShow($subdomain, $slug)
    {
        $product = Product::query()->where('slug', $slug)->available()->with('category', 'reviews')->first();

        if (!$product) {
            return $this->withMessage('Product not found', 404);
        }

        return $this->withJson(['data' => $product]);
    }

    public function index(Request $request)
    {
        $filter = $request->input('filter');
        $data = Product::query()->withDates()->owned()->with('category')
            ->with('category')->where(function ($query) use ($filter) {
                $query->where('name', "like", "%$filter%")->orWhere('description', 'like', "%$filter%")->with('category');
            });
        if (!$request->has('no-page')) {
            $data = $data->paginate();
            $data->appends($request->all());
        } else {
            $data = $data->get();
        }

        return $this->withJson(['data' => $data]);
    }

    public function getLatestProducts($subdomain, $count = 5)
    {
        return Product::query()->available()->limit($count)->latest()->get();
    }

    public function getFeaturedProducts($subdomain, $count = 5)
    {
        return Product::query()->available()->featured()->limit($count)->latest()->get();
    }

    public function getPopularProducts($subdomain, $count = 5)
    {
        return Product::query()->orderBy('search_count')->limit($count)->latest()->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:0',
            'description' => 'required',
            'features' => 'nullable',
            'sku' => 'nullable',
            'properties' => 'nullable|array',
            'properties.*.name' => 'required',
            'properties.*.value' => 'required',
            'images' => 'array',
            'variants' => 'nullable|array',
            'variants.*.name' => 'required',
            'variants.*.value' => 'required',
            'brand' => 'nullable|array',
            'brand.name' => 'nullable',
            'brand.country' => 'nullable',
        ]);

        $store = $this->getAuthUserStoreFromHeader();

        if (!$store) {
            return $this->withMessage('Store not found', 404);
        }

        if (Product::query()->owned()->where('name', $request->name)->count()) {
            return $this->withMessage('Product already exists', 404);
        }

        $data = $request->only('name', 'price', 'description', 'sku', 'category_id');
        $data['properties'] = json_encode($request->get('properties'));
        $data['images'] = json_encode($request->get('images'));
        $data['brand'] = json_encode($request->get('brand'));
        $data['variants'] = json_encode($request->get('variants'));
        $data['slug'] = Str::limit(Str::slug(($request->name)), 20, '') . '-' . time();
        $data['store'] = $store->slug;
        $data['quantity'] = $request->quantity;
        $data['main_features'] = $request->features;

        if ($product = Product::query()->create($data)) {
            if ($request->quantity) {
                ProductInventory::query()->create(['product_id' => $product->id, 'quantity' => $request->quantity,
                    'type' => 'stock', 'store' => $store->slug]);
            }

            $productCount = Product::query()->where('store', $store->slug)->count();
            $store->update(['total_products' => $productCount]);

            return $this->withJson(['data' => $product, 'message' => 'Product created successfully']);
        }

        return $this->withMessage("Unable to create product", 500);
    }

    public function show($subdomain, $slugId)
    {
        $product = Product::query()->with('category')->with('category', 'reviews')
            ->owned()->with('inventory')->where('slug', $slugId)->first();

        if (!$product) {
            return $this->withMessage('Product not found', 404);
        }

        return $this->withJson(['data' => $product]);
    }

    public function update(Request $request, $subdomain, $slugId)
    {
        $product = Product::query()->owned()->where('slug', $slugId)->first();

        if (!$product) {
            return $this->withMessage('Product not found', 404);
        }

        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:1',
            'description' => 'required',
            'features' => 'nullable',
            'sku' => 'nullable',
            'properties' => 'nullable|array',
            'properties.*.name' => 'required',
            'properties.*.value' => 'required',
            'variants' => 'nullable|array',
            'variants.*.name' => 'required',
            'variants.*.value' => 'required',
            'images' => 'array',
            'brand' => 'nullable|array',
            'brand.name' => 'nullable',
            'brand.country' => 'nullable',
        ]);

        if (Product::query()->owned()->where('id', '!=', $product->id)->where('name', $request->name)->count()) {
            return $this->withMessage('Product already exists', 404);
        }

        $data = $request->only('name', 'price', 'description', 'sku', 'category_id');
        $data['properties'] = json_encode($request->get('properties'));
        $data['images'] = json_encode($request->get('images'));
        $data['variants'] = json_encode($request->get('variants'));
        $data['brand'] = json_encode($request->get('brand'));
        $data['main_features'] = $request->get('features');

        if ($product->price != $request->price) {
            $data['old_price'] = $product->price;
        }

        if ($product->update($data)) {
            $product->load('category');
            return $this->withJson(['data' => $product, 'message' => 'Product updated successfully']);
        }

        return $this->withMessage("Unable to update product", 500);
    }

    public function destroy($subdomain, $slugId)
    {
        $product = Product::query()->owned()->where('slug', $slugId)->first();

        if (!$product) {
            return $this->withMessage('Product not found', 404);
        }

        if ($product->delete()) {
            $store = $product->storeObj;
            if ($store) {
                $productCount = Product::query()->where('store', $store->slug)->count();
                $store->update(['total_products' => $productCount]);
            }
            return $this->withJson(['message' => 'Product deleted successfully', 'data' => $product]);
        }

        return $this->withMessage('Unable to delete record, try again.', 500);
    }

    public function updateFeaturedStatus($subdomain, $slugId)
    {
        $product = Product::query()->with('category')->where('slug', $slugId)->owned()->first();

        if (!$product) {
            return $this->withMessage('Product not found', 404);
        }

        if ($product->update(['is_featured' => !$product->is_featured])) {
            return $this->withJson(['message' => 'Product updated successfully', 'data' => $product]);
        }

        return $this->withMessage('Unable to complete, try again.', 500);
    }

    public function updateActiveStatus($subdomain, $slugId)
    {
        $product = Product::query()->with('category')->where('slug', $slugId)->owned()->first();

        if (!$product) {
            return $this->withMessage('Product not found', 404);
        }

        if ($product->update(['active' => !$product->active])) {
            return $this->withJson(['message' => 'Product updated successfully', 'data' => $product]);
        }

        return $this->withMessage('Unable to complete, try again.', 500);
    }


}
