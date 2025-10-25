<?php

namespace App\Http\Requests\AuthValidation;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token'    => ['required', 'string'],
            'email'    => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'E‑posta adresi zorunlu.',
            'email.email' => 'Geçersiz e‑posta adresi.',
            'email.exists' => 'Bu e‑posta adresi ile kayıtlı bir kullanıcı bulunamadı.',
            'password.required' => 'Şifre zorunlu.',
            'password.string' => 'Geçersiz şifre.',
            'password.min' => 'Şifre en az 8 karakter uzunluğunda olmalıdır.',
            'password.confirmed' => 'Şifreler uyuşmuyor.',
        ];
    }
}
