<?php

namespace App\Http\Requests\Order\Refund;

use Illuminate\Foundation\Http\FormRequest;

class PaymentWebhookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => ['required', 'string', 'max:100'],
            'status'    => ['required', 'string', 'in:SUCCESS,FAILED'],
            'amount'    => ['required', 'numeric', 'min:0'],
            'currency'  => ['required', 'string', 'size:3'],
            'timestamp' => ['required', 'date'],
            'meta'      => ['nullable', 'array'],
        ];
    }
}
