<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function rules()
    {
        return ['email' => 'required|email'];
    }

    public function authorize()
    {
        return true;
    }
}
