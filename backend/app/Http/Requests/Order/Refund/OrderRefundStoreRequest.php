<?php

namespace App\Http\Requests\Order\Refund;

use Illuminate\Foundation\Http\FormRequest;

class OrderRefundStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:255'],
            'customer_note' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.order_item_id' => ['required', 'integer', 'exists:order_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.refund_amount_cents' => ['nullable', 'integer', 'min:0'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'İade sebebi zorunludur.',
            'reason.string' => 'İade sebebi metin formatında olmalıdır.',
            'reason.max' => 'İade sebebi en fazla 255 karakter olabilir.',
            'items.required' => 'En az bir ürün seçmelisiniz.',
            'items.array' => 'Ürün listesi geçersiz biçimde gönderildi.',
            'items.min' => 'En az bir ürün satırı vermelisiniz.',
            'items.*.order_item_id.required' => 'Her iade satırı için ürün kimliği gereklidir.',
            'items.*.order_item_id.integer' => 'Ürün kimliği geçersiz.',
            'items.*.order_item_id.exists' => 'Seçilen ürün siparişe ait değil.',
            'items.*.quantity.required' => 'İade adedi zorunludur.',
            'items.*.quantity.integer' => 'İade adedi sayı olmalıdır.',
            'items.*.quantity.min' => 'İade adedi en az 1 olmalıdır.',
            'items.*.refund_amount_cents.integer' => 'İade tutarı geçersiz.',
            'items.*.refund_amount_cents.min' => 'İade tutarı eksi olamaz.',
            'attachments.array' => 'Ek dosyalar geçersiz formatta.',
            'attachments.*.file' => 'Yüklenen dosya desteklenen formatta değil.',
            'attachments.*.mimes' => 'Dosya türü yalnızca jpg, jpeg, png veya pdf olabilir.',
            'attachments.*.max' => 'Dosya boyutu en fazla 2 MB olabilir.',
        ];
    }
}
