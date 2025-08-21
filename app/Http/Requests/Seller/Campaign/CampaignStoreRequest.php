<?php

namespace App\Http\Requests\Seller\Campaign;

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
            'type' => 'required|in:percentage,fixed,x_buy_y_pay',
            'description' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'priority' => 'nullable|integer|min:1|max:10',
            'usage_limit' => 'required|integer|min:0',
            'usage_limit_for_user' => 'required|integer|min:0',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'conditions' => 'nullable|array',
            'conditions.*.condition_type' => 'required|string|in:author,category,min_bag',
            'conditions.*.condition_value' => 'required',
            'conditions.*.operator' => 'required|string|in:=,!=,>,<,>=,<=,in,not_in',
            
            // Tek indirim sistemi - tip bazlı validasyon
            'discount_value' => 'required_if:type,percentage,fixed|integer|min:0',
            'discount_value.x' => 'required_if:type,x_buy_y_pay|integer|min:1',
            'discount_value.y' => 'required_if:type,x_buy_y_pay|integer|min:1',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Kampanya adı zorunludur.',
            'name.max' => 'Kampanya adı en fazla 255 karakter olmalıdır.',
            
            'type.required' => 'Kampanya tipi zorunludur.',
            'type.in' => 'Geçersiz kampanya tipi seçildi.',
            
            'description.max' => 'Açıklama en fazla 255 karakter olmalıdır.',
            
            'is_active.required' => 'Durum seçimi zorunludur.',
            
            'priority.integer' => 'Öncelik sayı olmalıdır.',
            'priority.min' => 'Öncelik en az 1 olmalıdır.',
            'priority.max' => 'Öncelik en fazla 10 olmalıdır.',
            
            'usage_limit.required' => 'Toplam kullanım limiti zorunludur.',
            'usage_limit.integer' => 'Toplam kullanım limiti sayı olmalıdır.',
            'usage_limit.min' => 'Toplam kullanım limiti en az 0 olmalıdır.',
            
            'usage_limit_for_user.required' => 'Kullanıcı başına limit zorunludur.',
            'usage_limit_for_user.integer' => 'Kullanıcı başına limit sayı olmalıdır.',
            'usage_limit_for_user.min' => 'Kullanıcı başına limit en az 0 olmalıdır.',
            
            'starts_at.required' => 'Başlangıç tarihi zorunludur.',
            'starts_at.date' => 'Geçerli bir başlangıç tarihi giriniz.',
            
            'ends_at.required' => 'Bitiş tarihi zorunludur.',
            'ends_at.date' => 'Geçerli bir bitiş tarihi giriniz.',
            'ends_at.after' => 'Bitiş tarihi başlangıç tarihinden sonra olmalıdır.',
            
            'conditions.array' => 'Koşullar dizi formatında olmalıdır.',
            'conditions.*.condition_type.required' => 'Koşul tipi zorunludur.',
            'conditions.*.condition_type.in' => 'Geçersiz koşul tipi seçildi.',
            'conditions.*.condition_value.required' => 'Koşul değeri zorunludur.',
            'conditions.*.operator.required' => 'Operatör zorunludur.',
            'conditions.*.operator.in' => 'Geçersiz operatör seçildi.',
            
            // İndirim mesajları
            'discount_value.required_if' => 'İndirim değeri zorunludur.',
            'discount_value.integer' => 'İndirim değeri sayı olmalıdır.',
            'discount_value.min' => 'İndirim değeri en az 0 olmalıdır.',
            
            'discount_value.x.required_if' => 'X değeri zorunludur.',
            'discount_value.y.required_if' => 'Y değeri zorunludur.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('starts_at')) {
            $this->merge([
                'starts_at' => $this->starts_at . ' 00:00:00'
            ]);
        }
        
        if ($this->has('ends_at')) {
            $this->merge([
                'ends_at' => $this->ends_at . ' 23:59:59'
            ]);
        }
    }
}
