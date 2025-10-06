<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShippingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session_id'           => ['required', 'uuid'],
            'shipping_address_id'  => ['required', 'exists:user_addresses,id'],
            'billing_address_id'   => ['nullable', 'exists:user_addresses,id'],
            'delivery_method'      => ['required', 'string', 'max:100'], // standard, express
            'notes'                => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'session_id.required'          => 'Checkout oturumu belirtilmelidir.',
            'session_id.uuid'              => 'Geçersiz session id formatı.',
            'shipping_address_id.required' => 'Teslimat adresi seçilmelidir.',
            'shipping_address_id.exists'   => 'Seçilen teslimat adresi bulunamadı.',
            'billing_address_id.exists'    => 'Seçilen fatura adresi bulunamadı.',
            'delivery_method.required'     => 'Teslimat yöntemi seçilmelidir.',
        ];
    }
}
