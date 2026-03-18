<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintRequest extends FormRequest
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
            "sales_entry_id" => "required",
            "party_id" => "required",
            // "product_id" => "required",
            "complaint_type_id" => "required",
            "status_id" => "required",
            "engineer_in_time" => ['after_or_equal:time', 'nullable'],
            "engineer_in_date" => "after_or_equal:date|nullable",
            "engineer_out_date" => "after_or_equal:engineer_in_date|nullable",
            "engineer_out_time" => "after_or_equal:engineer_in_time|nullable",
            "image" => "file|mimes:jpeg,jpg,png",
            "video" => "file|mimes:mp4,m4v,m4p,3gp",
            "audio" => "file|mimes:m4a,mp3,wav,aac",
        ];
    }
}
