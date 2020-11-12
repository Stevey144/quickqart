<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'username' => 'required|regex:/^[0-9A-Za-z._]+$/|unique:users',
            'phone_number' => 'nullable|digits_between:10,15|unique:users',
            'password' => 'required',
            'challenge' => 'nullable'
        ];

//        $user = auth()->user();
//        if ($user && $user->can(PermissionEnum::MANAGE_USERS)) {
//            unset($rules['challenge']);
//        }

        return $rules;
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'challenge.required' => 'Please confirm that you are not a robot.',
            'username.regex' => 'Specified username is not valid. Only characters and numbers are allowed.'
        ];
    }
}
