<?php

namespace App\Http\Requests\Admin\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class CampaignStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'user_activity' => 'required|boolean',
            'priority' => 'nullable|string|max:255',
            'usage_limit' => 'required|integer',
            'usage_limit_for_user' => 'required|integer',
            'user_usage' => 'nullable|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
    
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
            'user_activity.required' => 'Kullanıcı etkinliği alanı zorunludur.',
            'user_activity.boolean' => 'Kullanıcı etkinliği alanı boolean olmalıdır.',
            'priority.string' => 'Öncelik alanı metin olmalıdır.',
            'priority.max' => 'Öncelik alanı en fazla 255 karakter olmalıdır.',
            'usage_limit.required' => 'Kullanım limiti alanı zorunludur.',
            'usage_limit.integer' => 'Kullanım limiti alanı sayı olmalıdır.',
            'usage_limit_for_user.required' => 'Kullanıcı kullanım limiti alanı zorunludur.',
            'usage_limit_for_user.integer' => 'Kullanıcı kullanım limiti alanı sayı olmalıdır.',
            'user_usage.integer' => 'Kullanıcı kullanım alanı sayı olmalıdır.',
            'start_date.required' => 'Başlangıç tarihi alanı zorunludur.',
            'start_date.date' => 'Başlangıç tarihi alanı tarih olmalıdır.',
            'end_date.required' => 'Bitiş tarihi alanı zorunludur.',
            'end_date.date' => 'Bitiş tarihi alanı tarih olmalıdır.',

        ];
    }
}
