<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssueToCustomerRequest extends FormRequest
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
            "issue_date" => "required|date",
            "issue_time" => "required",
            "issue_engineer_id" => "required",
            "issue_remarks" => "required",
        ];
    }
}
