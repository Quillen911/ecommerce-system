<?php

namespace App\Http\Requests\AuthValidation;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'E‑posta adresi zorunlu.',
            'email.email' => 'Geçersiz e‑posta adresi.',
            'email.exists' => 'Bu e‑posta adresi ile kayıtlı bir kullanıcı bulunamadı.',
        ];
    }
}
