<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

class CreditCardStoreRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'card_number' => 'required|string|max:255',
            'expire_year' => 'required|string|max:255',
            'expire_month' => 'required|string|max:255',
            'card_type' => 'required|string|max:255',
            'card_holder_name' => 'required|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Ad alanı gereklidir.',
            'card_number.required' => 'Kart numarası alanı gereklidir.',
            'expire_year.required' => 'Son kullanma yılı alanı gereklidir.',
            'expire_month.required' => 'Son kullanma ayı alanı gereklidir.',
            'card_type.required' => 'Kart tipi alanı gereklidir.',
            'card_holder_name.required' => 'Kart sahibi adı alanı gereklidir.',
        ];
    }
}