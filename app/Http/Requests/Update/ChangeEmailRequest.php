<?php

namespace App\Http\Requests\Update;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ChangeEmailRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email', 'unique:users,email', 'max:255'],
            'current_password' => 'required'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator){
            if (!Hash::check($this->input('current_password'), auth()->user()->password)){
                $validator->errors()->add('current_password', __('settings.invalid_password'));
            }
        });
    }

    public function messages()
    {
        return [
            'email.required' => __('validation.email_required'),
            'email.email' => __('validation.email_format'),
            'email.unique' => __('validation.email_unique'),
            'current_password.required' => __('validation.password_required')
        ];
    }
}