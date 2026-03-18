<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MachineSalesEntryRequest extends FormRequest
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
            // "date" => "required|before_or_equal:today",
            "date" => "required",
            "party_id" => "required",
            //"mc_no" => "required",
            // "serial_no" => "required|unique:machine_sales_entries", //sr_no
            "serial_no" => "required", //sr_no
            // "install_date" => "required|after_or_equal:date",
            "install_date" => "required",
            "service_expiry_date" => "required",
            // "service_expiry_date" => "required|after_or_equal:install_date",
            //"order_no" => "required",
            "free_service" => "numeric|min:0|max:9",
            "service_type_id" => "required",
            // 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'map_url' => "active_url|nullable",
            "mic_fitting_engineer_id" => "required",
            "delivery_engineer_id" => "required",
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'serial_no.unique'         => 'M/c serial No already exists.',
        ];
    }
}
