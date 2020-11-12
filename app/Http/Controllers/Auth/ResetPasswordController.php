<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = "/login";

    protected function setUserPassword($user, $password)
    {
        $user->password = $password;
        $user->password_reset_code = '';
    }

    public function showResetByCode()
    {
        return view('auth.passwords.reset_by_code');
    }

    public function resetByCode(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'password_reset_code' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.

        $data = $request->only('password_reset_code');
        try {
            $data['phone_number'] = User::convertPhoneNumberToInternationalStandard($request->phone_number);
        } catch (\Exception $ex){
            return back()->withInput()->with('error', 'Unable to complete, try again');
        }

        $user = User::query()->where($data)->first();

        if (!$user) {
            return back()->withInput()->with('error', "No such request to reset password. Check phone number or code.");
        }
        $password = $request->get('password');
        $this->resetPassword($user, $password);

        return $this->sendResetResponse($request, Password::PASSWORD_RESET);
    }

    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));
    }


}
