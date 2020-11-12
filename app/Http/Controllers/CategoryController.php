<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query()->withDates()->owned();
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
        $data = Category::query()->paginate();
        $data->appends($request->all());

        return $data;
    }

    public function publicShow(Request $request, $subdomain, $slug)
    {
        $data = Category::query()->where('slug', $slug)->first();

        if (!$data) {
            return $this->withMessage('Category not found', 404);
        }

        return $this->withJson(['data' => $data]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'properties' => 'nullable|array',
            'properties.*.name' => 'required',
        ]);

        $store = $this->getAuthUserStoreFromHeader();

        if (!$store) {
            return $this->withMessage('Store not found', 404);
        }

        if (Category::query()->owned()->where('name', $request->name)->count()) {
            return $this->withMessage('Category already exists', 404);
        }

        $data = $request->only('name');
        $data['property'] = json_encode($request->get('properties'));
        $data['slug'] = Str::limit(Str::slug(($request->name)), 20, '') . '-' . time();
        $data['store'] = $store->slug;

        if ($category = Category::query()->create($data)) {
            return $this->withJson(['data' => $category, 'message' => 'Category created successfully']);
        }

        return $this->withMessage("Unable to create category", 500);
    }

    public function update(Request $request, $subdomain, $slug)
    {
        $category = Category::query()->where('slug', $slug)->first();

        if (!$category) {
            return $this->withMessage('Category not found', 404);
        }

        $this->validate($request, [
            'name' => 'required',
            'properties' => 'nullable|array',
            'properties.*.name' => 'required',
        ]);

        if (Category::query()->owned()->where('id', '!=', $category->id)->where('name', $request->name)->count()) {
            return $this->withMessage('Category already exists', 404);
        }

        $data = $request->only('name');
        $data['property'] = json_encode($request->get('properties'));

        if ($category->update($data)) {
            return $this->withJson(['data' => $category, 'message' => 'Updated successfully']);
        }

        return $this->withMessage("Unable to update category", 500);
    }

    public function destroy($subdomain, $slugId)
    {
        $category = Category::query()->owned()->where('slug', $slugId)->first();

        if (!$category) {
            return $this->withMessage('Category: ' . $slugId . ' not found', 404);
        }

        if ($category->delete()) {
            return $this->withJson(['message' => 'Category deleted successfully', 'data' => $category]);
        }

        return $this->withMessage('Unable to delete record, try again.', 500);

    }


}
