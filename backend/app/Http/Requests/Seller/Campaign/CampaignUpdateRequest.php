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
            'name'                  => 'sometimes|string|max:255',
            'type'                  => 'sometimes|string|in:percentage,fixed,x_buy_y_pay',
            'description'           => 'sometimes|nullable|string|max:255',
            'is_active'             => 'sometimes|boolean',
            'priority'              => 'nullable|integer|min:0',
            'usage_limit'           => 'sometimes|integer|min:0',
            'usage_limit_for_user'  => 'sometimes|integer|min:0',
            'starts_at'             => 'sometimes|date',
            'ends_at'               => 'sometimes|date|after_or_equal:starts_at',

            // Koşullar (güncellemede opsiyonel)
            'conditions'                    => 'sometimes|array',
            'conditions.*.condition_type'   => 'sometimes|string|max:255',
            'conditions.*.condition_value'  => 'sometimes',
            'conditions.*.operator'         => 'sometimes|string|max:255',

            // --- İndirim: TEK kayıt mantığı ---
            // Eğer type percentage/fixed ise tek integer bekle
            'discount_value'        => 'sometimes|nullable',

            // Eğer type x_buy_y_pay ise iki integer bekle
            'discount_value.x'      => 'sometimes|nullable',
            'discount_value.y'      => 'sometimes|nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'type.in'                         => 'Tip yalnızca yüzde, sabit veya X al Y öde olabilir.',
            'ends_at.after_or_equal'          => 'Bitiş tarihi başlangıç tarihinden önce olamaz.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->filled('starts_at')) {
            $this->merge(['starts_at' => trim($this->starts_at).' 00:00:00']);
        }
        if ($this->filled('ends_at')) {
            $this->merge(['ends_at' => trim($this->ends_at).' 23:59:59']);
        }

        if ($this->has('is_active')) {
            $this->merge(['is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? (in_array($this->is_active, ['1',1,true], true))]);
        }
        
        // discount_value temizleme - sadece boş string kontrolü
        if ($this->has('discount_value') && $this->input('discount_value') === '') {
            $this->merge(['discount_value' => null]);
        }
        
        // discount_value.x ve discount_value.y temizleme
        if ($this->has('discount_value.x') && $this->input('discount_value.x') === '') {
            $this->merge(['discount_value' => ['x' => null]]);
        }
        
        if ($this->has('discount_value.y') && $this->input('discount_value.y') === '') {
            $this->merge(['discount_value' => ['y' => null]]);
        }
    }
}
