<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateLeaveRequest extends FormRequest
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
            'firm_id' => ['required'],
            'year_id' => ['required'],
            'user_id' => ['required'],
            'date_time' => ['required'],
            'leave_from' => ['required', 'date'],
            'leave_till' => ['required', 'date', 'after_or_equal:leave_from'],
            'total_leave' => ['required', 'numeric'],
            'reason' => ['required'],
            'is_approved' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'leave_till.after_or_equal' => 'The leave till date must be a date after or equal to the leave from date.',
        ];
    }
}
