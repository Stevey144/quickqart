<?php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{

    public function showHome()
    {
        return view('parent.index');
    }

    public function index() {
        if ($this->subdomain === 'control-panel') {
            return view('store.control-panel');
        } else if ($this->subdomain === 'setup') {
            return view('store.setup');
        } else if ($this->subdomain === 'store') {
            return view('store.store');
        } else if (strtolower($this->subdomain) === 'www') {
            return redirect('http://localhost:8000');
        }

        $store = Store::query()->where('slug', $this->subdomain)->first();

        if (!$store) {
            return abort(404, 'Store not found');
        }

        return view('store.index', ['store' => $store]);
    }

    public function terms() {
        return view('parent.terms');
    }

    public function privacy() {
        return view('parent.privacy');
    }


}
