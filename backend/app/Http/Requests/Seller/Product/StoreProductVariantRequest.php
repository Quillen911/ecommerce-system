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

            'sizes' => 'required|array|min:1',
            'sizes.*.size_option_id' => 'required|integer|min:0',
            'sizes.*.price_cents' => 'sometimes|integer|min:0',
            'sizes.*.inventory.*.on_hand' => 'required|integer|min:0',
            'sizes.*.inventory.*.reserved' => 'sometimes|integer|min:0',
            'sizes.*.inventory.*.warehouse_id' => 'sometimes|integer|min:0',
        ];
    }

    public function payload(): array
    {
        return $this->except(['sku', 'slug','images']);
    }

    public function messages()
    {
        return [
            "color_name.required" => "Renk adı zorunludur.",
            "color_name.string" => "Renk adı metin olmalıdır.",
            "color_name.max" => "Renk adı en fazla 120 karakter olmalıdır.",
            "color_code.regex" => "Geçersiz renk kodu.",
            "price_cents.required" => "Fiyat zorunludur.",
            "price_cents.integer" => "Fiyat sayı olmalıdır.",
            "price_cents.min" => "Fiyat en az 0 olmalıdır.",
            "is_popular.boolean" => "Boolean olmalıdır.",
            "is_active.boolean" => "Boolean olmalıdır.",
            "sizes.array" => "Bedenler dizi olmalıdır.",
            "sizes.*.size_option_id.required" => "Beden seçimi zorunludur.",
            "sizes.*.size_option_id.integer" => "Beden ID sayı olmalıdır.",
            "sizes.*.size_option_id.min" => "Beden ID en az 0 olmalıdır.",
            "sizes.*.price_cents.integer" => "Beden fiyatı sayı olmalıdır.",
            "sizes.*.price_cents.min" => "Beden fiyatı en az 0 olmalıdır.",
            "sizes.*.inventory.*.on_hand.required" => "Stokta bulunan miktar zorunludur.",
            "sizes.*.inventory.*.on_hand.integer" => "Stokta bulunan miktar sayı olmalıdır.",
            "sizes.*.inventory.*.on_hand.min" => "Stokta bulunan miktar en az 0 olmalıdır.",
            "sizes.*.inventory.*.reserved.integer" => "Rezerve miktarı sayı olmalıdır.",
            "sizes.*.inventory.*.warehouse_id.integer" => "Depo ID sayı olmalıdır.",
        ];
    }
}
