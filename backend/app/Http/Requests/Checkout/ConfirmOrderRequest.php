<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;
//kullanılmıyor
//kullanılmıyor
//kullanılmıyor
//kullanılmıyor

//kullanılmıyor
//kullanılmıyor

class ConfirmOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'          => ['nullable', 'string'],
            'paymentId'       => ['required', 'string'],
            'conversationId'  => ['required', 'string'],
            'conversationData'=> ['nullable', 'string'],
            'mdStatus'        => ['nullable', 'string'],
            'signature'       => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'paymentId.required'      => 'Ödeme kimliği zorunludur.',
            'conversationId.required' => 'ConversationId bilgisi zorunludur.',
        ];
    }
}
