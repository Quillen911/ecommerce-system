<?php

namespace App\Http\Requests\AuthValidation;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|min:3|max:100',
            'last_name' => 'required|string|min:3|max:100',
            'username' => 'required|string|min:3|max:50|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|regex:/^[0-9+\-\s()]+$/|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Ad zorunludur.',
            'first_name.max' => 'Ad en fazla 100 karakter olabilir.',
            'last_name.required' => 'Soyad zorunludur.',
            'last_name.max' => 'Soyad en fazla 100 karakter olabilir.',
            'username.required' => 'Kullanıcı adı zorunludur.',
            'username.min' => 'Kullanıcı adı en az 3 karakter olmalıdır.',
            'username.max' => 'Kullanıcı adı en fazla 50 karakter olabilir.',
            'username.unique' => 'Bu kullanıcı adı zaten kullanılıyor.',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
            'password.required' => 'Şifre zorunludur.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifre onayı eşleşmiyor.',
            'phone.regex' => 'Geçerli bir telefon numarası giriniz.',
            'phone.max' => 'Telefon numarası en fazla 20 karakter olabilir.',
        ];
    }
}
