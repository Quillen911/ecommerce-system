<?php

namespace App\Http\Requests\Admin\Campaign;

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


        ];
    }
}
