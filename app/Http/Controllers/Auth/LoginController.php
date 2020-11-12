<?php

namespace App\Http\Controllers\Auth;

use App\Enums\EventTypeEnum;
use App\Events\LocalNotifyAdminEvent;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Role;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        return $this->sendLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $data = $request->email;
        try {
            $data = User::convertPhoneNumberToInternationalStandard($data);
            $type = 'phone_number';
        } catch (\Exception $ex){
            if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
                $type = 'email';
            } else {
                $type = 'username';
            }
        }

        $credentials = $request->only(['password']);
        $credentials[$type] = $data;

        try {
            $token = Auth::guard()->attempt($credentials);

            if (!$token) {
                $this->reportEvent(EventTypeEnum::FAILED_LOGIN);
                $this->incrementLoginAttempts($request);

                if ($request->wantsJson()) {
                    return $this->withMessage('These credentials do not match our records', 403);
                } else {
                    return back()->withInput()->with('error', 'These credentials do not match our records');
                }
            }
        } catch (JWTException $e) {
            if ($request->wantsJson()) {
                return $this->withMessage($e->getMessage(), 500);
            } else {
                return back()->withInput()->with('error', $e->getMessage());
            }
        }

        $this->clearLoginAttempts($request);

        $user = User::query()->where($type, $data)->first();

        if (($suspensionRecord = $user->suspension)) {
            event(new LocalNotifyAdminEvent('While being locked out, user: ' . $user->name . ' attempted to login to his/her account'));

            if ($request->wantsJson()) {
                return $this->withMessage('Your has account has been suspended for the reason that: ' . $suspensionRecord->reason, 400);
            } else {
                return back()->with('error', 'Your has account has been suspended for the reason that: ' . $suspensionRecord->reason);
            }
        }

        if (!$user->email_verified_at) {
            $user->sendEmailVerificationNotification();
            if ($user->phone_number) {
                $message = "we have sent an activation code to <strong>$user->phone_number</strong>.";
            } else {
                $message = "We have sent you a new activation link to <strong>$user->email</strong>";
            }
            if ($request->wantsJson()) {
                return $this->withMessage("This account is yet to be activated. $message", 400);
            } else {
                return back()->withInput()->with(['error' => "This account is yet to be activated. $message"]);
            }
        }

        $this->reportEvent(EventTypeEnum::LOGGED_IN, $user);

        if ($request->wantsJson()) {
            return $this->withJson([
                'token' => $token,
                'id_token' => json_encode($user->getClaim())
            ]);
        } else {
            $subDomain = 'store';
            if (!$user->hasRole(Role::SHOP_OWNER)) {
                $subDomain = 'control-panel';
            } else if ($user->stores()->count() == 0) {
                $subDomain = 'setup';
            }

            $domain = env("APP_BASE_URL", 'localhost:8000');

            return redirect("http://$subDomain.$domain/auth/$token");
        }
    }
}
