<?php

namespace App\Http\Controllers;

use App\Faq;
use App\Permission;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $items = Faq::query();

        if (($sortBy = $request->get('sort_by')) && ($dir = $request->get('sort_dir'))) {
            $items->orderBy($sortBy, $dir);
        }

        $items = $items->get();

        if ($request->expectsJson()) {
            return $this->withJson([
                'data' => $items
            ]);
        }
        return view('parent.faq', compact('items'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can(Permission::MANAGE_FAQS)) {
            return $this->permissionDenied();
        }

        $this->validate($request, [
            'question' => 'required|unique:faqs,question',
            'answer' => 'required',
        ]);

        $faq = Faq::query()->create($request->only('question', 'answer'));

        if ($faq) {
            return $this->withJson(['data' => $faq, 'New FAQ added successfully']);
        }

        return $this->withMessage('Unable to complete, try again', 500);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can(Permission::MANAGE_FAQS)) {
            return $this->permissionDenied();
        }

        $faq = Faq::query()->find($id);

        if (!$faq) {
            return $this->withMessage('Faq not found', 404);
        }

        $this->validate($request, [
            'question' => 'required|unique:faqs,question,' . $id,
            'answer' => 'required',
        ]);

        if ($faq->update($request->only('question', 'answer'))) {
            return $this->withJson(['data' => $faq, 'message' => 'FAQ updated successfully']);
        }

        return $this->withMessage('Unable to complete, try again', 500);
    }

    public function destroy($id)
    {
        if (!auth()->user()->can(Permission::MANAGE_FAQS)) {
            return $this->permissionDenied();
        }

        $faq = Faq::query()->find($id);

        if (!$faq) {
            return $this->withMessage('Faq not found', 404);
        }

        if ($faq->delete()) {
            return $this->withMessage("FAQ deleted successfully.");
        }

        return $this->withMessage('Unable to complete, try again', 500);
    }
}
