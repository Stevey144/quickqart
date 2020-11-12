<?php

namespace App\Http\Controllers;

use App\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index($subdomain, $productId) {
        return ProductReview::query()->where('product_id', $productId)->paginate();
    }

    public function store(Request $request,$subdomain, $productId) {
        $this->validate($request, [
           'rating' => 'required|numeric',
           'author' => 'required',
           'email' => 'required|email',
           'content' => 'required',
        ]);

        $data = $request->only('rating', 'author', 'email', 'content');
        $data['product_id'] = $productId;
        $data['ip'] = $request->ip();

        $review = ProductReview::query()->create($data);

        return $this->withJson(['data' => $review]);
    }

}
