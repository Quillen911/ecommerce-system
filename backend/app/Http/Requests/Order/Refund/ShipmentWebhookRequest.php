<?php

namespace App\Http\Requests\Order\Refund;

use Illuminate\Foundation\Http\FormRequest;

class ShipmentWebhookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // signature doğrulaması middleware’de yapılacak
    }

    public function rules(): array
    {
        return [
            'tracking_number' => ['required', 'string', 'max:100'],
            'status'          => ['required', 'string', 'in:PICKED_UP,IN_TRANSIT,DELIVERED,FAILED'],
            'timestamp'       => ['required', 'date'],
            'meta'            => ['nullable', 'array'],
        ];
    }
}
