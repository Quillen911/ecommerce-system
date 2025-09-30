<?php

namespace App\Http\Requests\Seller\Product;

use Illuminate\Foundation\Http\FormRequest;

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
            'meta_title' => 'sometimes|nullable|string|max:255',
            'meta_description' => 'sometimes|nullable|string|max:160',
            'is_published' => 'sometimes|boolean',

            'variants' => 'sometimes|array|min:1',
            'variants.*.id' => 'required_with:variants.*.price,variants.*.stock_quantity,variants.*.attributes|exists:product_variants,id',
            'variants.*.price' => 'sometimes|numeric|min:0',
            'variants.*.stock_quantity' => 'sometimes|integer|min:0',
            'variants.*.is_popular' => 'sometimes|boolean',
            'variants.*.is_active' => 'sometimes|boolean',

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

            'variants.*.is_active.boolean' => 'Varyant aktiflik boolean olmalıdır.',

            'meta_title.string' => 'Meta title metin olmalıdır.',
            'meta_title.max' => 'Meta title en fazla 255 karakter olmalıdır.',
            'meta_description.string' => 'Meta description metin olmalıdır.',
            'meta_description.max' => 'Meta description en fazla 160 karakter olmalıdır.',

            'is_published.boolean' => 'Yayın durumu boolean olmalıdır.',

            'variants.array' => 'Variants dizi olmalıdır.',
            'variants.*.id.exists' => 'Geçersiz varyant.',
            'variants.*.price.numeric' => 'Fiyat sayı olmalıdır.',
            'variants.*.price.min' => 'Fiyat en az 0 olmalıdır.',
            'variants.*.stock_quantity.integer' => 'Stok miktarı sayı olmalıdır.',
            'variants.*.stock_quantity.min' => 'Stok en az 0 olmalıdır.',
            'variants.*.is_popular.boolean' => 'Varyant popülerlik boolean olmalıdır.',
            'variants.*.is_active.boolean' => 'Varyant aktiflik boolean olmalıdır.',
            'variants.*.attributes.array' => 'Varyant özellikleri dizi olmalıdır.',
            'variants.*.attributes.*.attribute_id.exists' => 'Geçersiz attribute.',
            'variants.*.attributes.*.option_id.exists' => 'Geçersiz attribute option.',
        ];
    }
}
