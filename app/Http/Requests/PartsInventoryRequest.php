<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartsInventoryRequest extends FormRequest
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
            "date" => "required|date",
            "time" => "required",
            "in_engineer_id" => "required",
            "in_party_id" => "required",
            "product_id" => "required",
            "card_no" => "required",
            "remarks" => "required",
        ];
    }
}
