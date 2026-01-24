<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc,dns',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please enter an email.',
            'email.email'    => 'Please enter a valid email.',
        ];
    }
}