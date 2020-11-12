<?php

namespace App\Http\Controllers\Auth;

use App\Events\LocalNotifyAdminEvent;
use App\Http\Controllers\Controller;
use App\Notifications\StoreOwnerRegistrationNotification;
use App\Providers\RouteServiceProvider;
use App\Role;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    private $isEmailRegistration = false;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        if (session()->has('reg_completed')) {
            return view('auth.reg_completed');
        } else {
            return view('auth.register');
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $dataRule = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'unique:users,phone_number'],
            'password' => ['required', 'string', 'min:8'],
        ];

        $isEmail = false;
        $d = strtolower($data['email']);

        if (filter_var($d, FILTER_VALIDATE_EMAIL)) {
            $isEmail = true;
            $dataRule['email'] = ['required', 'string', 'email', 'max:255', 'unique:users,email'];
        } else {
            try {
                $phone = User::convertPhoneNumberToInternationalStandard($data['email']);

                if (User::query()->where('phone_number', $phone)->count()) {
                    return back()->withInput()->with('error', "Phone number has been taken");
                }

            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Invalid phone number supplied');
            }
        }

        $validationMessage = [
            'email.required' => 'An email or phone number is required',
            'email.unique' => 'The phone number provided has already been taken',
            'email.digits_between' => 'The phone number must be between 10 to 15 digits long'
        ];

        if ($isEmail) {
            $validationMessage = [
                'email.required' => 'An email or phone number is required',
                'email.unique' => 'The email  provided has already been taken',
            ];
        }

        return Validator::make($data, $dataRule, $validationMessage);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        if ($user->phone_number) {
            return redirect(route('activate.token'))->with('phone_number', $user->phone_number);
        }

        return redirect(route('register'))->with('user', $user->id)
            ->with('reg_completed', "A link has been sent to <strong>$user->email</strong> for account verification.");
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $rData = request()->only('name', 'password');

        $email = strtolower(request()->get('email'));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $rData['phone_number'] = User::convertPhoneNumberToInternationalStandard($email);
        } else {
            $rData['email'] = $email;
        }

        $rData['activation_code'] = rand(10000, 99999);

        $user = new User($rData);
        $user->save();
        $role = Role::query()->where('name', Role::SHOP_OWNER)
            ->where('guard_name', 'api')->first();
        $user->assignRole($role);

        $user->notify(new StoreOwnerRegistrationNotification());

        event(new LocalNotifyAdminEvent("A new user by name $user->name and email $user->email has just signed up as a shop owner."));

        return $user;
    }
}
