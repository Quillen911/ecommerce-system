<?php

namespace App\Http\Requests\Seller\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active'  => filter_var($this->input('is_active', true), FILTER_VALIDATE_BOOLEAN),
            'is_popular' => filter_var($this->input('is_popular', false), FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function rules(): array
    {
        return [
            'color_name'  => ['required', 'string', 'max:120'],
            'color_code'  => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'price_cents' => ['required', 'integer', 'min:0'],
            'is_popular'  => ['sometimes', 'boolean'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }

    public function payload(): array
    {
        return $this->except(['sku', 'slug']);
    }
}
