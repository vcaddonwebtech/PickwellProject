<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RepairInRequest extends FormRequest
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
            "repair_in_date" => "required|date",
            "repair_in_time" => "required",
            "repair_in_remarks" => "required",
        ];
    }
}
