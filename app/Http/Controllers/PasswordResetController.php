<?php

namespace App\Http\Controllers;

use App\AccountActivation;
use App\Events\LocalNotifyAdminEvent;
use App\PasswordReset;
use App\User;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{

    public function doChange(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = auth()->user();
        if (!password_verify($request->current_password, $user->password)) {
            return $this->withMessage('Access denied', 400);
        }

        $user->password = $request->get('password');
        if ($user->save()) {
            return $this->withMessage('Password updated successfully.');
        }
        return $this->withMessage('Unable to complete request, try again', 500);
    }

}
