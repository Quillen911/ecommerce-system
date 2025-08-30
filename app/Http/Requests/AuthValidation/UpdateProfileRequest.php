<?php

namespace App\Http\Requests\AuthValidation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
            'phone.regex' => 'Geçerli bir telefon numarası giriniz.',
            'phone.max' => 'Telefon numarası en fazla 20 karakter olabilir.',
            'address.max' => 'Adres en fazla 255 karakter olabilir.',
            'city.max' => 'Şehir adı en fazla 100 karakter olabilir.',
            'district.max' => 'İlçe adı en fazla 100 karakter olabilir.',
            'postal_code.max' => 'Posta kodu en fazla 10 karakter olabilir.',
        ];
    }
}
