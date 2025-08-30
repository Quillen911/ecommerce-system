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
            'username' => 'required|string|min:3|max:50|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|regex:/^[0-9+\-\s()]+$/|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
        ];
    }

    public function messages(): array
    {
        return [
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
            'address.max' => 'Adres en fazla 255 karakter olabilir.',
            'city.max' => 'Şehir adı en fazla 100 karakter olabilir.',
            'district.max' => 'İlçe adı en fazla 100 karakter olabilir.',
            'postal_code.max' => 'Posta kodu en fazla 10 karakter olabilir.',
        ];
    }
}
