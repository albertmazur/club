<?php

namespace App\Http\Requests\Update;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'current_password' => 'required',
            'password' => ['required', 'min:8', 'confirmed', 'different:current_password']
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
            'current_password.required' => __('validation.current_password_required'),
            'password.required' => __('validation.new_password_required'),
            'password.min' => __('validation.min.string'),
            'password.confirmed' => __('validation.password_confirmed'),
            'password.different' => __('validation.password_different')
        ];
    }
}