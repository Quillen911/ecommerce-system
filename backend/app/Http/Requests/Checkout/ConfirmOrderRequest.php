<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // auth middleware zaten kontrol ediyor
    }

    public function rules(): array
    {
        return [
            'status'           => ['required', 'string'],
            'paymentId'        => ['required', 'string'],
            'conversationId'   => ['required', 'string'],
            'mdStatus'         => ['nullable', 'string'],
            'conversationData' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'session_id.required'        => 'Checkout oturumu belirtilmelidir.',
            'session_id.uuid'            => 'Geçersiz session id formatı.',
            'payment_intent_id.required' => 'Ödeme bilgisi eksik.',
        ];
    }
}
