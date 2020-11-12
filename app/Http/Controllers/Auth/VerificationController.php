<?php

namespace App\Http\Controllers\Auth;

use App\Events\LocalNotifyAdminEvent;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    protected $redirectTo = '/login';

    public function __construct()
    {
//        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show(Request $request, $id)
    {
        $user = User::query()->find($id);

        if (!$user) {
            return abort(404, "User record not found.");
        }

        return $user->hasVerifiedEmail()
            ? redirect($this->redirectPath())->with('verified', 'Your email has already been verified')
            : view('auth.verify');
    }

    public function verify(Request $request, $id)
    {
        $user = User::query()->find($id);

        if (!$user) {
            return abort(404, "User record not found.");
        }

        if (!hash_equals((string)$request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new Response('', 204)
                : redirect($this->redirectPath());
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        event(new LocalNotifyAdminEvent("$user->name has just confirmed his/her account via the link sent to $user->email."));
        if ($response = $this->verified($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect($this->redirectPath())->with('verified', 'Your account has been activated successfully, you can now login to your account.');
    }

    public function activateByCode(Request $request)
    {
        $this->validate($request, [
            'phone_number' => 'required',
            'activation_code' => 'required',
        ]);

        $phone = User::convertPhoneNumberToInternationalStandard($request->phone_number);

        $user = User::query()->where('phone_number', $phone)->first();

        if (!$user) {
            return back()->with('error', "User record does not exist.");
        }

        if ($user->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new Response('', 204)
                : redirect(url('/login'))->with('error', 'Account has been activated earlier');
        }

        if ($user->activation_code != $request->activation_code) {
            return back()->with('error', 'Invalid activation code');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        event(new LocalNotifyAdminEvent("$user->name has just confirmed his/her account via the link sent to $user->email."));
        if ($response = $this->verified($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect($this->redirectPath())->with('verified', 'Your account has been activated successfully, you can now login to your account.');
    }

    public function resend(Request $request)
    {
        $user = User::query()->find($request->get('id'));

        if (!$user) {
            return abort(404, "User record not found.");
        }

        if ($user->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new Response('', 204)
                : redirect($this->redirectPath());
        }

        $user->sendEmailVerificationNotification();

        return $request->wantsJson()
            ? new Response('', 202)
            : back()->with('resent', true);
    }

    public function resendCode(Request $request, $phone_number)
    {
        $phone = User::convertPhoneNumberToInternationalStandard($request->phone_number);

        $user = User::query()->where('phone_number', $phone)->first();

        if (!$user) {
            return back()->with('error', "User account not found.");
        }

        if ($user->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new Response('', 204)
                : redirect(url('/login'))->with('error', 'Account has been activated earlier');
        }

        $user->update(['activation_code' => rand(10000, 99999)]);

        $user->sendEmailVerificationNotification();

        return $request->wantsJson()
            ? new Response('', 202)
            : back()->with('resent', true)->with('phone_number', $user->phone_number)
                ->with('status', 'A new activation code has been sent.');
    }
}
