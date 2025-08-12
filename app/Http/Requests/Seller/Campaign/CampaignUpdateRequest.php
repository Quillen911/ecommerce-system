<?php

namespace App\Http\Requests\Seller\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class CampaignUpdateRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:255',
            'is_active' => 'sometimes|boolean',
            'priority' => 'nullable|string|max:255',
            'usage_limit' => 'sometimes|integer',
            'usage_limit_for_user' => 'sometimes|integer',
            'starts_at' => 'sometimes|date',
            'ends_at' => 'sometimes|date',
            'conditions' => 'sometimes|array',
            'discounts' => 'sometimes|array',
            'conditions.*.condition_type' => 'sometimes|string|max:255',
            'conditions.*.condition_value' => 'sometimes',
            'conditions.*.operator' => 'sometimes|string|max:255',
            'discounts.*.discount_type' => 'sometimes|string|max:255',
            'discounts.*.discount_value' => 'sometimes',
            'discounts.*.applies_to' => 'sometimes|string|max:255',
    
        ];
    }
    public function messages(): array
    {
        return [
            'name.string' => 'Ad alanı metin olmalıdır.',
            'name.max' => 'Ad alanı en fazla 255 karakter olmalıdır.',
            'type.string' => 'Tip alanı metin olmalıdır.',
            'type.max' => 'Tip alanı en fazla 255 karakter olmalıdır.',
            'description.string' => 'Açıklama alanı metin olmalıdır.',
            'description.max' => 'Açıklama alanı en fazla 255 karakter olmalıdır.',
            'is_active.boolean' => 'Aktiflik alanı boolean olmalıdır.',
            'usage_limit.integer' => 'Kullanım limiti alanı sayı olmalıdır.',
            'usage_limit_for_user.integer' => 'Kullanıcı kullanım limiti alanı sayı olmalıdır.',
            'starts_at.date' => 'Başlangıç tarihi alanı tarih olmalıdır.',
            'ends_at.date' => 'Bitiş tarihi alanı tarih olmalıdır.',
            'conditions.array' => 'Koşullar alanı dizi olmalıdır.',
            'discounts.array' => 'İndirimler alanı dizi olmalıdır.',
            'conditions.*.condition_type.string' => 'Koşul tipi alanı metin olmalıdır.',
            'conditions.*.condition_type.max' => 'Koşul tipi alanı en fazla 255 karakter olmalıdır.',
            'conditions.*.condition_value.string' => 'Koşul değeri alanı metin olmalıdır.',
            'conditions.*.operator.string' => 'Operatör alanı metin olmalıdır.',
            'conditions.*.operator.max' => 'Operatör alanı en fazla 255 karakter olmalıdır.',


        ];
    }
}
