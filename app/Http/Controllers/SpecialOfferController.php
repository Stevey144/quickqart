<?php

namespace App\Http\Controllers;

use App\SpecialOffer;
use App\SpecialOfferProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SpecialOfferController extends Controller
{
    public function index(Request $request)
    {
        $query = SpecialOffer::query()->latest()->owned();
        if ($request->input('pagination') === 'none') {
            $data = $query->get();
        } else {
            $data = $query->paginate();
            $data->appends($request->all());
        }

        return $this->withJson(['data' => $data]);
    }

    public function publicIndex(Request $request)
    {
        $data = SpecialOffer::query()->paginate();
        $data->appends($request->all());

        return $data;
    }

    public function show(Request $request, $subdomain, $slug)
    {
        $data = SpecialOffer::query()->with(['offerProducts' => function ($query) {
            $query->with('product');
        }])->where('slug', $slug)->first();

        if (!$data) {
            return $this->withMessage('SpecialOffer not found', 404);
        }

        return $this->withJson(['data' => $data]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'start' => 'required',
            'end' => 'required',
            'banners' => 'required|array',
            'discount' => 'required|digits_between:0,100',
            'threshold' => 'required',
            'offer_products' => 'nullable|array',
            'offer_products.*.product_id' => 'required|exists:products,id',
        ]);

        $store = $this->getAuthUserStoreFromHeader();

        if (!$store) {
            return $this->withMessage('Store not found', 404);
        }

        if (SpecialOffer::query()->owned()->where($request->only('title', 'start', 'end'))->count()) {
            return $this->withMessage('Special Offer already exists', 404);
        }

        $data = $request->only('title', 'banners', 'description', 'store', 'start', 'end', 'discount', 'threshold');
        $data['banners'] = json_encode($request->get('banners'));
        $data['slug'] = Str::limit(Str::slug(($request->title)), 50, '') . '-' . time();
        $data['store'] = $store->slug;

        if ($specialOffer = SpecialOffer::query()->create($data)) {

            $products = (array) $request->get('offer_products');

            foreach ($products as $product) {
                SpecialOfferProduct::query()->firstOrCreate([
                    'special_offer_id' => $specialOffer->id,
                    'special_offer_slug' => $specialOffer->slug,
                    'product_id' => $product['product_id'],
                    'store' => $store->slug
                ], [
                    'discount' => $product['discount'], 'threshold' => $product['threshold']
                ]);
            }

            return $this->withJson(['data' => $specialOffer, 'message' => 'SpecialOffer created successfully']);
        }

        return $this->withMessage("Unable to create SpecialOffer", 500);
    }

    public function update(Request $request, $subdomain, $slug)
    {
        $specialOffer = SpecialOffer::query()->where('slug', $slug)->first();

        if (!$specialOffer) {
            return $this->withMessage('SpecialOffer not found', 404);
        }

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'start' => 'required',
            'end' => 'required',
            'banners' => 'required|array',
            'discount' => 'required|digits_between:0,100',
            'threshold' => 'required',
            'offer_products' => 'nullable|array',
            'offer_products.*.product_id' => 'required|exists:products,id',
        ]);

        $store = $this->getAuthUserStoreFromHeader();

        if (!$store) {
            return $this->withMessage('Store not found', 404);
        }

        if (SpecialOffer::query()->owned()->where('id', '!=', $specialOffer->id)
            ->where($request->only('title', 'start', 'end'))->count()) {
            return $this->withMessage('Special Offer already exists', 404);
        }

        $data = $request->only('title', 'banners', 'description', 'store', 'start', 'end', 'discount', 'threshold');
        $data['banners'] = json_encode($request->get('banners'));
        $data['store'] = $store->slug;

        if ($specialOffer->update($data)) {

            $products = (array) $request->get('offer_products');

            $excepts = [];
            foreach ($products as $product) {
                $sp = SpecialOfferProduct::query()->updateOrCreate([
                    'special_offer_id' => $specialOffer->id,
                    'special_offer_slug' => $specialOffer->slug,
                    'product_id' => $product['product_id'],
                    'store' => $store->slug
                ], [
                    'discount' => $product['discount'], 'threshold' => $product['threshold']
                ]);

                $excepts[] = $sp->id;
            }



            return $this->withJson(['data' => $specialOffer, 'message' => 'Special Offer updated successfully']);
        }

        return $this->withMessage("Unable to update Special Offer", 500);
    }

    public function destroy($subdomain, $slugId)
    {

//        return $this->withMessage($slugId, 400);
        $SpecialOffer = SpecialOffer::query()->where('slug', $slugId)->first();


        if (!$SpecialOffer) {
            return $this->withMessage('SpecialOffer: ' . $slugId . ' not found', 404);
        }

        if ($SpecialOffer->delete()) {
            return $this->withJson(['message' => 'SpecialOffer deleted successfully', 'data' => $SpecialOffer]);
        }

        return $this->withMessage('Unable to delete record, try again.', 500);

    }
}
