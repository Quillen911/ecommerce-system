<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        $rules = [
            'credit_card_id' => 'required',
            'shipping_address_id' => 'required|exists:user_addresses,id',
            'billing_address_id' => 'required|exists:user_addresses,id',
        ];

        if ($this->input('credit_card_id') === 'new_card') {
            $rules = array_merge($rules, [
                'new_card_holder_name' => 'required|string|max:255',
                'new_card_name' => 'required|string|max:255',
                'new_card_number' => 'required|string|size:16',
                'new_expire_month' => 'required|string|size:2',
                'new_expire_year' => 'required|string|size:4',
                'new_cvv' => 'required|string|size:3',
                'save_new_card' => 'sometimes|boolean'
            ]);
        } else {
            // Check if the selected card requires CVV
            $creditCardId = $this->input('credit_card_id');
            if ($creditCardId && $creditCardId !== 'new_card') {
                $creditCard = \App\Models\CreditCard::find($creditCardId);
                if ($creditCard && !$creditCard->iyzico_card_token) {
                    $rules['existing_cvv'] = 'required|string|size:3';
                }
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'credit_card_id.required' => 'Lütfen bir ödeme yöntemi seçiniz.',
            'shipping_address_id.required' => 'Lütfen bir teslimat adresi seçiniz.',
            'billing_address_id.required' => 'Lütfen bir fatura adresi seçiniz.',
            'new_card_holder_name.required' => 'Kart sahibi adı gereklidir.',
            'new_card_name.required' => 'Kart ismi gereklidir.',
            'new_card_number.required' => 'Kart numarası gereklidir.',
            'new_card_number.size' => 'Kart numarası 16 haneli olmalıdır.',
            'new_expire_month.required' => 'Son kullanma ayı gereklidir.',
            'new_expire_month.size' => 'Son kullanma ayı 2 haneli olmalıdır.',
            'new_expire_year.required' => 'Son kullanma yılı gereklidir.',
            'new_expire_year.size' => 'Son kullanma yılı 4 haneli olmalıdır.',
            'new_cvv.required' => 'CVV kodu gereklidir.',
            'new_cvv.size' => 'CVV kodu 3 haneli olmalıdır.',
            
            'existing_cvv.required' => 'CVV kodu gereklidir.',
            'existing_cvv.size' => 'CVV kodu 3 haneli olmalıdır.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Geçersiz istek.',
            'errors' => $validator->errors(),
        ], 422));
    }
}