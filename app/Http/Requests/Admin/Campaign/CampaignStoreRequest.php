<?php

namespace App\Http\Requests\Admin\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class CampaignStoreRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'priority' => 'nullable|string|max:255',
            'usage_limit' => 'required|integer',
            'usage_limit_for_user' => 'required|integer',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
            'conditions' => 'required|array',
            'discounts' => 'required|array',
            'conditions.*.condition_type' => 'required|string|max:255',
            'conditions.*.condition_value' => 'required',
            'conditions.*.operator' => 'required|string|max:255',
            'discounts.*.discount_type' => 'required|string|max:255',
            'discounts.*.discount_value' => 'required',
            'discounts.*.applies_to' => 'required|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Ad alanı zorunludur.',
            'name.string' => 'Ad alanı metin olmalıdır.',
            'name.max' => 'Ad alanı en fazla 255 karakter olmalıdır.',
            'type.required' => 'Tip alanı zorunludur.',
            'type.string' => 'Tip alanı metin olmalıdır.',
            'type.max' => 'Tip alanı en fazla 255 karakter olmalıdır.',
            'description.required' => 'Açıklama alanı zorunludur.',
            'description.string' => 'Açıklama alanı metin olmalıdır.',
            'description.max' => 'Açıklama alanı en fazla 255 karakter olmalıdır.',
            'is_active.required' => 'Aktiflik alanı zorunludur.',
            'is_active.boolean' => 'Aktiflik alanı boolean olmalıdır.',
            'priority.string' => 'Öncelik alanı metin olmalıdır.',
            'priority.max' => 'Öncelik alanı en fazla 255 karakter olmalıdır.',
            'usage_limit.required' => 'Kullanım limiti alanı zorunludur.',
            'usage_limit.integer' => 'Kullanım limiti alanı sayı olmalıdır.',
            'usage_limit_for_user.required' => 'Kullanıcı kullanım limiti alanı zorunludur.',
            'usage_limit_for_user.integer' => 'Kullanıcı kullanım limiti alanı sayı olmalıdır.',
            'starts_at.required' => 'Başlangıç tarihi alanı zorunludur.',
            'starts_at.date' => 'Başlangıç tarihi alanı tarih olmalıdır.',
            'ends_at.required' => 'Bitiş tarihi alanı zorunludur.',
            'ends_at.date' => 'Bitiş tarihi alanı tarih olmalıdır.',

        ];
    }
}
