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
        $size    = $this->route('size');

        return [
            'size_option_id' => [
                'sometimes',
                'integer',
                Rule::unique('variant_sizes', 'size_option_id')
                    ->where(fn ($query) => $query->where('product_variant_id', $variant?->id))
                    ->ignore($size?->id)
            ],
            'price_cents' => ['sometimes', 'integer', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],

            'inventory.on_hand'         => ['sometimes', 'integer', 'min:0'],
            'inventory.reserved'        => ['sometimes', 'integer', 'min:0'],
            'inventory.min_stock_level' => ['sometimes', 'integer', 'min:0'],
            'inventory.warehouse_id'    => ['sometimes', 'integer', 'exists:warehouses,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'size_option_id.integer' => 'Beden ID sayı olmalıdır.',
            'size_option_id.unique'  => 'Bu beden ID zaten kullanılıyor.',
            'price_cents.integer'    => 'Fiyat sayı olmalıdır.',
            'price_cents.min'        => 'Fiyat en az 0 olmalıdır.',
            'is_active.boolean'      => 'Boolean olmalıdır.',

            'inventory.on_hand.integer'         => 'Stokta bulunan miktar sayı olmalıdır.',
            'inventory.on_hand.min'             => 'Stokta bulunan miktar en az 0 olmalıdır.',
            'inventory.reserved.integer'        => 'Rezerve miktarı sayı olmalıdır.',
            'inventory.min_stock_level.integer' => 'Minimum stok seviyesi sayı olmalıdır.',
            'inventory.warehouse_id.integer'    => 'Depo ID sayı olmalıdır.',
            'inventory.warehouse_id.exists'     => 'Depo bulunamadı.',
        ];
    }

    public function variantSizePayload(): array
    {
        $size = $this->route('size');

        return collect([
            'size_option_id' => $this->has('size_option_id')
                ? $this->input('size_option_id')
                : $size?->size_option_id,
            'price_cents' => $this->has('price_cents')
                ? $this->input('price_cents')
                : $size?->price_cents,
            'is_active' => $this->has('is_active')
                ? $this->input('is_active')
                : $size?->is_active,
        ])->filter(fn ($value) => !is_null($value))->all();
    }

    public function inventoryPayload(): array
    {
        $size = $this->route('size');

        return collect([
            'warehouse_id' => $this->has('inventory.warehouse_id')
                ? $this->input('inventory.warehouse_id')
                : $size?->inventory?->warehouse_id,
            'on_hand' => $this->has('inventory.on_hand')
                ? $this->input('inventory.on_hand')
                : $size?->inventory?->on_hand,
            'reserved' => $this->has('inventory.reserved')
                ? $this->input('inventory.reserved')
                : $size?->inventory?->reserved,
            'min_stock_level' => $this->has('inventory.min_stock_level')
                ? $this->input('inventory.min_stock_level')
                : $size?->inventory?->min_stock_level,
        ])->filter(fn ($value) => !is_null($value))->all();
    }
}
