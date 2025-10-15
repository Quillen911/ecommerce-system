<?php

namespace App\Http\Requests\Seller\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVariantSizeRequest extends FormRequest
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
    }

    public function rules(): array
    {
        $variant = $this->route('variant');
        $variantSize = $this->route('size');

        return [
            'size_option_id' => [
                'sometimes',
                Rule::unique('variant_sizes')
                    ->where('product_variant_id', $this->route('variant')->id),
            ],            
            'sku' => [
                'sometimes',
                'nullable',
                'string',
                'max:191',
                Rule::unique('product_variant_sizes', 'sku')
                    ->where('product_variant_id', $variant?->id)
                    ->ignore($variantSize?->id),
            ],
            'price_cents' => ['sometimes', 'integer', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],

            'inventory.on_hand'          => ['sometimes', 'integer', 'min:0'],
            'inventory.reserved'         => ['sometimes', 'integer', 'min:0'],
            'inventory.min_stock_level'  => ['sometimes', 'integer', 'min:0'],
            'inventory.warehouse_id'     => ['sometimes', 'integer', 'exists:warehouses,id'],
        ];
    }

    public function variantSizePayload(): array
    {
        return collect([
            'size_option_id' => $this->input('size_option_id'),
            'sku'            => $this->input('sku'),
            'price_cents'    => $this->input('price_cents'),
            'is_active'      => $this->input('is_active'),
        ])->filter(fn ($value) => !is_null($value))->all();
    }

    public function inventoryPayload(): array
    {
        return collect([
            'warehouse_id'     => $this->input('inventory.warehouse_id'),
            'on_hand'          => $this->input('inventory.on_hand'),
            'reserved'         => $this->input('inventory.reserved'),
            'min_stock_level'  => $this->input('inventory.min_stock_level'),
        ])->filter(fn ($value) => !is_null($value))->all();
    }
}
