<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:30', 'string'],
            'qty' => ['required','integer','min:1'],
            'alert_qty' => ['nullable','integer', 'min:1'],
            'ingredients' => ['required', 'min:3', 'string'],
            'location_id' => ['required','exists:locations,id'],
            'production_date' => ['required','date'],
            'subLocation_id' => ['required','exists:sub_locations,id'],
        ];
    }
}
