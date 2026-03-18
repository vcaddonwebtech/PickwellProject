<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RepairOutRequest extends FormRequest
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
            "repair_out_date" => "required|date",
            "repair_out_time" => "required",
            "repair_out_party_code" => "required",
            "repair_out_party_id" => "required",
            "repair_out_remarks" => "required",
            "expexted_required_date" => "required",
        ];
    }
}
