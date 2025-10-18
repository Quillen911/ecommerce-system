<?php

namespace App\Http\Requests\Seller\Campaign;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampaignUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $campaignId = $this->route('id');

        return [
            'name' => 'sometimes|required|string|max:255',
            'code' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('campaigns')->ignore($campaignId),
            ],
            'description' => 'sometimes|nullable|string',
            'type' => ['sometimes', 'required', Rule::in(['percentage', 'fixed', 'x_buy_y_pay'])],
            
            'discount_value' => 'sometimes|required_if:type,percentage,fixed|nullable|numeric|min:0|max:100',
            
            'buy_quantity' => 'sometimes|required_if:type,x_buy_y_pay|nullable|integer|min:1',
            'pay_quantity' => 'sometimes|required_if:type,x_buy_y_pay|nullable|integer|min:0|lt:buy_quantity',

            'min_subtotal' => 'sometimes|nullable|numeric|min:0',
            'usage_limit' => 'sometimes|nullable|integer|min:1',
            'per_user_limit' => 'sometimes|nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
            'starts_at' => 'sometimes|nullable|date',
            'ends_at' => 'sometimes|nullable|date|after:starts_at',

            'product_ids' => 'sometimes|nullable|array',
            'product_ids.*' => 'integer|exists:products,id',
            'category_ids' => 'sometimes|nullable|array',
            'category_ids.*' => 'integer|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Kampanya adı gereklidir.',
            'name.string' => 'Kampanya adı metin formatında olmalıdır.',
            'name.max' => 'Kampanya adı en fazla 255 karakter olabilir.',

            'code.string' => 'Kampanya kodu metin formatında olmalıdır.',
            'code.max' => 'Kampanya kodu en fazla 255 karakter olabilir.',
            'code.unique' => 'Bu kampanya kodu zaten kullanılmaktadır.',

            'description.string' => 'Açıklama metin formatında olmalıdır.',

            'type.required' => 'Kampanya tipi gereklidir.',
            'type.in' => 'Kampanya tipi geçersizdir. Geçerli tipler: percentage, fixed, x_buy_y_pay.',

            'discount_value.required_if' => 'İndirim değeri gereklidir.',
            'discount_value.numeric' => 'İndirim değeri sayısal olmalıdır.',
            'discount_value.min' => 'İndirim değeri 0\'dan büyük olmalıdır.',
            'discount_value.max' => 'İndirim değeri en fazla %100 olmalıdır.',

            'buy_quantity.required_if' => 'Alınacak miktar gereklidir.',
            'buy_quantity.integer' => 'Alınacak miktar tam sayı olmalıdır.',
            'buy_quantity.min' => 'Alınacak miktar en az 1 olmalıdır.',

            'pay_quantity.required_if' => 'Ödenecek miktar gereklidir.',
            'pay_quantity.integer' => 'Ödenecek miktar tam sayı olmalıdır.',
            'pay_quantity.min' => 'Ödenecek miktar 0\'dan büyük veya eşit olmalıdır.',
            'pay_quantity.lt' => 'Ödenecek miktar alınacak miktardan küçük olmalıdır.',

            'min_subtotal.numeric' => 'Minimum tutar sayısal olmalıdır.',
            'min_subtotal.min' => 'Minimum tutar 0\'dan büyük veya eşit olmalıdır.',

            'usage_limit.integer' => 'Kullanım limiti tam sayı olmalıdır.',
            'usage_limit.min' => 'Kullanım limiti en az 1 olmalıdır.',

            'is_active.boolean' => 'Aktiflik durumu doğru/yanlış değeri olmalıdır.',

            'starts_at.date' => 'Başlangıç tarihi geçerli bir tarih formatında olmalıdır.',

            'ends_at.date' => 'Bitiş tarihi geçerli bir tarih formatında olmalıdır.',
            'ends_at.after' => 'Bitiş tarihi başlangıç tarihinden sonra olmalıdır.',

            'product_ids.array' => 'Ürün listesi dizi formatında olmalıdır.',
            'product_ids.*.integer' => 'Ürün ID\'si tam sayı olmalıdır.',
            'product_ids.*.exists' => 'Seçilen ürün bulunamadı.',

            'category_ids.array' => 'Kategori listesi dizi formatında olmalıdır.',
            'category_ids.*.integer' => 'Kategori ID\'si tam sayı olmalıdır.',
            'category_ids.*.exists' => 'Seçilen kategori bulunamadı.',
        ];
    }
}
