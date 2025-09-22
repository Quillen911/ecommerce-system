<?php

namespace App\Http\Requests\Seller\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|nullable|exists:categories,id',
            'description' => 'sometimes|nullable|string',
            'meta_description' => 'sometimes|nullable|string|max:160',
            'list_price' => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'sold_quantity' => 'sometimes|integer|min:0',

            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            // Varyantlar
            'variants' => 'sometimes|array|min:1',
            'variants.*.sku' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('product_variants', 'sku')->ignore($this->variant_id ?? null),
            ],
            'variants.*.price' => 'sometimes|numeric|min:0',
            'variants.*.stock_quantity' => 'sometimes|integer|min:0',
            'variants.*.images' => 'sometimes|array|min:1',
            'variants.*.images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            // Varyant attribute validasyonu
            'variants.*.attributes' => 'sometimes|array|min:1',
            'variants.*.attributes.*.attribute_id' => 'sometimes|exists:attributes,id',
            'variants.*.attributes.*.option_id' => 'nullable|exists:attribute_options,id',
            'variants.*.attributes.*.value' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'Ürün adı metin olmalıdır.',
            'title.max' => 'Ürün adı en fazla 255 karakter olmalıdır.',
            'category_id.exists' => 'Geçersiz kategori.',

            'list_price.numeric' => 'Liste fiyatı sayı olmalıdır.',
            'list_price.min' => 'Liste fiyatı en az 0 olmalıdır.',

            'stock_quantity.integer' => 'Stok miktarı sayı olmalıdır.',
            'stock_quantity.min' => 'Stok miktarı en az 0 olmalıdır.',

            'sold_quantity.integer' => 'Satılan miktar sayı olmalıdır.',
            'sold_quantity.min' => 'Satılan miktar en az 0 olmalıdır.',

            'images.array' => 'Resimler dizi olmalıdır.',
            'images.*.image' => 'Resimler resim dosyası olmalıdır.',
            'images.*.mimes' => 'Resimler jpeg, png, jpg, gif, svg formatında olmalıdır.',
            'images.*.max' => 'Resimler en fazla 2MB olmalıdır.',

            'variants.array' => 'Variants dizi olmalıdır.',
            'variants.*.sku.string' => 'SKU metin olmalıdır.',
            'variants.*.sku.max' => 'SKU en fazla 255 karakter olmalıdır.',
            'variants.*.sku.unique' => 'SKU benzersiz olmalıdır.',
            'variants.*.price.numeric' => 'Fiyat sayı olmalıdır.',
            'variants.*.price.min' => 'Fiyat en az 0 olmalıdır.',
            'variants.*.stock_quantity.integer' => 'Stok miktarı sayı olmalıdır.',
            'variants.*.stock_quantity.min' => 'Stok en az 0 olmalıdır.',
            'variants.*.images.array' => 'Varyant resimleri dizi olmalıdır.',
            'variants.*.images.*.image' => 'Varyant resimleri dosya olmalıdır.',
            'variants.*.images.*.mimes' => 'Varyant resimleri jpeg, png, jpg, gif, svg formatında olmalıdır.',
            'variants.*.images.*.max' => 'Varyant resimleri en fazla 2MB olmalıdır.',

            'variants.*.attributes.array' => 'Varyant özellikleri dizi olmalıdır.',
            'variants.*.attributes.*.attribute_id.exists' => 'Geçersiz attribute.',
            'variants.*.attributes.*.option_id.exists' => 'Geçersiz attribute option.',
            'variants.*.attributes.*.value.string' => 'Attribute değeri metin olmalıdır.',
        ];
    }
}
