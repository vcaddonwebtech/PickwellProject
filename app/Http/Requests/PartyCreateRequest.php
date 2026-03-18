<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Party;
use App\Models\PartyFirm;


class PartyCreateRequest extends FormRequest
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
            "name" => [
                "required" 
                //Rule::unique(Party::class)->ignore($this->route('party') ? $this->route('party')->id : null)
            ],
            "firm_name" => [
                "required"
                //Rule::unique(PartyFirm::class)->ignore($this->route('party') ? $this->route('party')->id : null)
            ],
            "address" => "required",
            "area_id" => "required",
            "phone_no" => [
                "required",
                "numeric",
                "digits:10",
            ],
        ];
    }
}
