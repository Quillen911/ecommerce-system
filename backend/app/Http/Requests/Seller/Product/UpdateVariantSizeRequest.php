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
        return [
            'size_option_id' => [
                'sometimes',
                Rule::unique('variant_sizes')
                    ->where('product_variant_id', $this->route('variant')->id),
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

    public function messages()
    {
        return [
            "size_option_id.exists" => "Seçilen beden bulunamadı.",
            "price_cents.integer" => "Fiyat sayı olmalıdır.",
            "price_cents.min" => "Fiyat en az 0 olmalıdır.",
            "is_active.boolean" => "Boolean olmalıdır.",
            "inventory.on_hand.integer" => "Stokta bulunan miktar sayı olmalıdır.",
            "inventory.on_hand.min" => "Stokta bulunan miktar en az 0 olmalıdır.",
            "inventory.reserved.integer" => "Rezerve miktarı sayı olmalıdır.",
            "inventory.min_stock_level.integer" => "Minimum stok seviyesi sayı olmalıdır.",
            "inventory.warehouse_id.integer" => "Depo ID sayı olmalıdır.",
            "inventory.warehouse_id.exists" => "Depo bulunamadı.",
        ];
    }
}
