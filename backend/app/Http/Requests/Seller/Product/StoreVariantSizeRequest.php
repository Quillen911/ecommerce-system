<?php

namespace App\Http\Requests\Seller\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVariantSizeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policy kullanıyorsan burada kontrol et.
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => filter_var($this->input('is_active', true), FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function rules(): array
    {
        $variant = $this->route('variant');

        return [
            'size_option_id' => [
                'required',
                Rule::unique('variant_sizes')
                    ->where('product_variant_id', $this->route('variant')->id),
            ],
            'sku' => [
                'nullable',
                'string',
                'max:191',
                Rule::unique('product_variant_sizes', 'sku')
                    ->where('product_variant_id', $variant?->id),
            ],
            'price_cents' => ['required', 'integer', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],

            'inventory.on_hand'          => ['required', 'integer', 'min:0'],
            'inventory.reserved'         => ['nullable', 'integer', 'min:0'],
            'inventory.min_stock_level'  => ['nullable', 'integer', 'min:0'],
            'inventory.warehouse_id'     => ['nullable', 'integer', 'exists:warehouses,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'size_option_id.exists' => 'Seçilen beden bulunamadı.',
        ];
    }

    public function variantSizePayload(): array
    {
        return collect([
            'size_option_id' => $this->integer('size_option_id'),
            'sku'            => $this->input('sku'),
            'price_cents'    => $this->integer('price_cents'),
            'is_active'      => (bool) $this->input('is_active', true),
        ])->filter(fn ($value) => !is_null($value))->all();
    }

    public function inventoryPayload(): array
    {
        return [
            'warehouse_id'     => $this->integer('inventory.warehouse_id') ?? 1,
            'on_hand'          => $this->integer('inventory.on_hand'),
            'reserved'         => $this->integer('inventory.reserved') ?? 0,
            'min_stock_level'  => $this->integer('inventory.min_stock_level') ?? 0,
        ];
    }
}
