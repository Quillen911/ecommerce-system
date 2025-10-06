<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class GetSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session_id' => ['required', 'uuid'],
        ];
    }

    public function messages(): array
    {
        return [
            'session_id.required' => 'Checkout oturumu belirtilmelidir.',
            'session_id.uuid'     => 'Geçersiz session id formatı.',
        ];
    }
}
