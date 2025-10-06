<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // iyzico mock veya gerçek 3D callback yapısına göre
        return [
            'orderId'     => ['required', 'string'],
            'bin'         => ['nullable', 'string'],
            'PaReq'       => ['nullable', 'string'],
            'smsVerified' => ['nullable', 'string'],
            'Xid'         => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'orderId.required' => 'orderId (sipariş kimliği) zorunludur.',
        ];
    }

}
