<?php

namespace App\Http\Requests;

use App\Enums\Language;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class TranslateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('isAdminOrModerator', 'role');;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text'   => ['required', 'string'],
            'source' => ['required', new Enum(Language::class)],
            'target' => ['required', new Enum(Language::class)],
        ];
    }
}
