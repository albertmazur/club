<?php

namespace App\Http\Requests\Delete;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class DeleteMyAccountRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'password' => 'required',
            'confirm_delete' => 'accepted'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator){
            if(!Hash::check($this->input('password'), auth()->user()->password)){
                $validator->errors()->add('password', __('settings.invalid_password'));
            }
            if(auth()->user()->role == UserRole::ADMIN->value){
                $validator->errors()->add('password', __('settings.invalid_password'));
            }
        });
    }

    public function messages()
    {
        return [
            'password.required' => __('validation.password_required'),
            'confirm_delete.accepted' => __('validation.confirm_delete_required')
        ];
    }
}