<?php

namespace App\Http\Requests\Seller\Order;

use Illuminate\Foundation\Http\FormRequest;

class SellerRefundItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'İade sebebi zorunludur.',
            'reason.string' => 'İade sebebi metin formatında olmalıdır.',
            'reason.max' => 'İade sebebi en fazla 255 karakter olabilir.',
            'quantity.required' => 'İade adedi zorunludur.',
            'quantity.integer' => 'İade adedi sayı olmalıdır.',
            'quantity.min' => 'İade adedi en az 1 olmalıdır.',
        ];
    }
}
