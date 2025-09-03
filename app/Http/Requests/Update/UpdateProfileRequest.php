<?php

namespace App\Http\Requests\Update;

use App\Enums\Language;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('isAdmin', 'role');
    }

    public function rules()
    {
        return [
            'id' => ['nullable', 'exists:users,id'],
            'first_name' => ['required', 'string', 'max:255', 'min:2'],
            'last_name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->input('id'))],
            'tel' => ['required', 'string', 'max:20', 'regex:/^\+?[0-9\- ]{7,20}$/', Rule::unique('users', 'tel')->ignore($this->input('id'))],
            'language' => ['required', Rule::enum(Language::class)],
            'role' => ['nullable', Rule::enum(UserRole::class)],
            'password' => ['nullable', 'min:8', 'confirmed'],
            'change_password' => ['nullable', 'boolean']
        ];
    }

    public function messages()
    {
        return [
            'language.required' => __('validation.language_required'),
            'language.in' => __('validation.language_invalid'),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator){
            $editedUser = User::find($this->input('id'));

            if ($editedUser && $editedUser->role === UserRole::ADMIN->value && $this->input('role') !== UserRole::ADMIN->value)
            {
                $adminCount = User::where('role', UserRole::ADMIN->value)->where('id', '!=', $editedUser->id)->count();

                if ($adminCount < 1)
                {
                    $validator->errors()->add('role', __('validation.at_least_one_admin'));
                }
            }
        });
    }
}