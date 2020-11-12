<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\ResetPasswordByCodeNotification;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $data = $request->email;
        try {
            $data = User::convertPhoneNumberToInternationalStandard($data);
            $type = 'phone_number';
        } catch (\Exception $ex){
            $type = 'email';
        }


        if ($type === 'email') {
            $this->validate($request, [
                'email' => 'required|email'
            ]);
        }

        if ($type === 'email') {
            $response = $this->broker()->sendResetLink(
                $this->credentials($request)
            );

            return $response == Password::RESET_LINK_SENT
                ? $this->sendResetLinkResponse($request, $response)
                : $this->sendResetLinkFailedResponse($request, $response);
        } else {
            $user = User::query()->where('phone_number', $data)->first();

            if (!$user) {
                return back()->withInput()->with('error', "The phone number provided is not associated with any account.");
            }

            if ($user->update(['password_reset_code' => rand(10000, 99999)])) {
                $user->notify(new ResetPasswordByCodeNotification());
                return redirect(route('password.reset.code'))->with("phone_number", $user->phone_number)
                    ->with('status', "A reset code has been sent to $user->phone_number");
            }

            return back()->withInput()->with('error', "Unable to complete, try again");
        }
    }

    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required']);
    }
}
