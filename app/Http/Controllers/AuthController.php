<?php

namespace App\Http\Controllers;

use App\Role;
use App\Store;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function verifyToken(Request $request) {
        $subDomain = $this->subdomain;

        if (!auth()->check()) {
            return $this->withMessage('Invalid login', 400);
        }

        $user = auth()->user();

        if ($subDomain === 'control-panel' && $user->hasRole(Role::SHOP_OWNER)) {
            return $this->withMessage('Invalid login', 400);
        } else if ($subDomain !== 'control-panel' && !$user->hasRole(Role::SHOP_OWNER)) {
            return $this->withMessage('Invalid login', 400);
        }

        return $this->withJson([
            'data' => $user->getClaim(),
            'stores' => $user->stores()->get()
        ]);


    }
}
