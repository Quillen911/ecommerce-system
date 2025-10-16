<?php

namespace App\Http\Requests\Seller\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVariantSizeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
                'integer',
                'min:1',
                'exists:attribute_options,id',
                Rule::unique('variant_sizes', 'size_option_id')
                    ->where(fn ($query) => $query->where('product_variant_id', $variant?->id)),
            ],
            'price_cents' => ['required', 'integer', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],

            'inventory.on_hand'         => ['required', 'integer', 'min:0'],
            'inventory.reserved'        => ['nullable', 'integer', 'min:0'],
            'inventory.min_stock_level' => ['nullable', 'integer', 'min:0'],
            'inventory.warehouse_id'    => ['nullable', 'integer', 'exists:warehouses,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'size_option_id.required' => 'Beden ID zorunludur.',
            'size_option_id.integer'  => 'Beden ID sayı olmalıdır.',
            'size_option_id.min'      => 'Beden ID en az 1 olmalıdır.',
            'size_option_id.exists'   => 'Seçilen beden bulunamadı.',
            'size_option_id.unique'   => 'Bu beden ID bu varyantta zaten kullanılıyor.',
            'price_cents.required'    => 'Fiyat zorunludur.',
            'price_cents.integer'     => 'Fiyat sayı olmalıdır.',
            'price_cents.min'         => 'Fiyat en az 0 olmalıdır.',
            'is_active.boolean'       => 'Aktiflik bilgisi boolean olmalıdır.',

            'inventory.on_hand.required' => 'Stoktaki miktar zorunludur.',
            'inventory.on_hand.integer'  => 'Stoktaki miktar sayı olmalıdır.',
            'inventory.on_hand.min'      => 'Stoktaki miktar en az 0 olmalıdır.',
            'inventory.reserved.integer' => 'Rezerve miktarı sayı olmalıdır.',
            'inventory.reserved.min'     => 'Rezerve miktarı en az 0 olmalıdır.',
            'inventory.min_stock_level.integer' => 'Minimum stok seviyesi sayı olmalıdır.',
            'inventory.min_stock_level.min'     => 'Minimum stok seviyesi en az 0 olmalıdır.',
            'inventory.warehouse_id.integer'    => 'Depo ID sayı olmalıdır.',
            'inventory.warehouse_id.exists'     => 'Depo bulunamadı.',
        ];
    }

    public function variantSizePayload(): array
    {
        return collect([
            'size_option_id' => $this->integer('size_option_id'),
            'price_cents'    => $this->integer('price_cents'),
            'is_active'      => (bool) $this->input('is_active', true),
        ])->filter(fn ($value) => !is_null($value))->all();
    }

    public function inventoryPayload(): array
    {
        return [
            'warehouse_id'    => $this->integer('inventory.warehouse_id') ?? 1,
            'on_hand'         => $this->integer('inventory.on_hand'),
            'reserved'        => $this->integer('inventory.reserved') ?? 0,
            'min_stock_level' => $this->integer('inventory.min_stock_level') ?? 0,
        ];
    }
}
