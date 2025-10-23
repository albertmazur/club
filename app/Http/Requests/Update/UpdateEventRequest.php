<?php

namespace App\Http\Requests\Update;

use App\Enums\Language;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('isAdmin', 'role');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => ['required' , 'integer'],
            'name' => ['required', 'string'],
            'description' => ['array', Rule::in(array_column(Language::cases(), 'value'))],
            'description.*' => ['nullable', 'string'],
            'date' => ['required', 'date', 'after:today'],
            'time' => ['required', 'date_format:H:i'],
            'price' => ['required', 'decimal:0,2'],
            'stadium_id' => ['required', 'integer'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048']
        ];
    }
}
