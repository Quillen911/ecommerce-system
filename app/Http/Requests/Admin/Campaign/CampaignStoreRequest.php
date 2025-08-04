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
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Ad alanı zorunludur.',
            'type.required' => 'Tip alanı zorunludur.',
            'is_active.required' => 'Aktiflik alanı zorunludur.',
            'usage_limit.required' => 'Kullanım limiti alanı zorunludur.',
            'usage_limit_for_user.required' => 'Kullanıcı kullanım limiti alanı zorunludur.',
            'starts_at.required' => 'Başlangıç tarihi alanı zorunludur.',
            'ends_at.required' => 'Bitiş tarihi alanı zorunludur.',
            'conditions.required' => 'Koşullar alanı zorunludur.',
            'discounts.required' => 'İndirimler alanı zorunludur.',
            'conditions.*.condition_type.required' => 'Koşul tipi alanı zorunludur.',
            'conditions.*.condition_value.required' => 'Koşul değeri alanı zorunludur.',
            'conditions.*.operator.required' => 'Operatör alanı zorunludur.',
            'discounts.*.discount_type.required' => 'İndirim tipi alanı zorunludur.',
            'discounts.*.discount_value.required' => 'İndirim değeri alanı zorunludur.',
        ];
    }
}
