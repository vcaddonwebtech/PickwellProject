<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreTodoRequest extends FormRequest
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
            'title' => 'required | max:255',
            'description' => 'required',
            'user_id' => 'required',
            'assign_user_id' => 'integer | nullable',
            'assign_date_time' => 'nullable',
            'priority_id' => 'nullable',
            'pdf' => 'file|mimes:pdf',
            'image' => 'file|mimes:jpeg,jpg,png',
            'video' => 'file|mimes:mp4,m4v,m4p,3gp',
            'audio' => 'file|mimes:m4a,mp3,wav,aac',
        ];
    }
}
