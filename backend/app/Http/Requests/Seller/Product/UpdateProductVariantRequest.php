<?php

namespace App\Http\Requests\Seller\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        if ($this->has('is_popular')) {
            $this->merge([
                'is_popular' => filter_var($this->input('is_popular'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'color_name'  => ['sometimes', 'string', 'max:120'],
            'color_code'  => ['sometimes', 'nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'price_cents' => ['sometimes', 'integer', 'min:0'],
            'is_popular'  => ['sometimes', 'boolean'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }

    public function payload(): array
    {
        return collect($this->except(['sku', 'slug']))
            ->filter(fn ($value) => !is_null($value))
            ->all();
    }
}
