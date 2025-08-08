<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

class CreditCardUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'card_number' => 'sometimes|string|max:255',
            'cvv' => 'sometimes|string|max:255',
            'expire_year' => 'sometimes|string|max:255',
            'expire_month' => 'sometimes|string|max:255',
            'card_type' => 'sometimes|string|max:255',
            'card_holder_name' => 'sometimes|string|max:255',
            'is_active' => 'sometimes|boolean',
        ];
    }
    public function messages()
    {
        return [
            'name.sometimes' => 'Ad alanı gereklidir.',
            'card_number.sometimes' => 'Kart numarası alanı gereklidir.',
            'cvv.sometimes' => 'CVV alanı gereklidir.',
            'expire_year.sometimes' => 'Son kullanma yılı alanı gereklidir.',
            'expire_month.sometimes' => 'Son kullanma ayı alanı gereklidir.',
            'card_type.sometimes' => 'Kart tipi alanı gereklidir.',
            'card_holder_name.sometimes' => 'Kart sahibi adı alanı gereklidir.',
            'is_active.sometimes' => 'Aktif alanı gereklidir.',
        ];
    }
}