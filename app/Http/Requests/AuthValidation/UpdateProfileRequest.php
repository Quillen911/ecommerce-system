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
            'first_name' => 'required|string|min:3|max:100',
            'last_name' => 'required|string|min:3|max:100',
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
            'phone.regex' => 'Geçerli bir telefon numarası giriniz.',
            'phone.max' => 'Telefon numarası en fazla 20 karakter olabilir.',
        ];
    }
}
