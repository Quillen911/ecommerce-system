<?php

namespace App\Http\Requests\Seller\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'author' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
            'list_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            // Varyantlar
            'variants' => 'required|array|min:1',
            'variants.*.sku' => 'required|string|max:255|unique:product_variants,sku',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.images' => 'required|array|min:1',
            'variants.*.images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            // Varyant attribute validasyonu
            'variants.*.attributes' => 'required|array|min:1',
            'variants.*.attributes.*.attribute_id' => 'required|exists:attributes,id',
            'variants.*.attributes.*.option_id' => 'nullable|exists:attribute_options,id',
            'variants.*.attributes.*.value' => 'nullable|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Ürün adı boş bırakılamaz.',
            'title.string' => 'Ürün adı metin olmalıdır.',
            'title.max' => 'Ürün adı en fazla 255 karakter olmalıdır.',
            'category_id.exists' => 'Geçersiz kategori.',

            'list_price.required' => 'Liste fiyatı boş bırakılamaz.',
            'list_price.numeric' => 'Liste fiyatı sayı olmalıdır.',
            'list_price.min' => 'Liste fiyatı en az 0 olmalıdır.',

            'stock_quantity.required' => 'Stok miktarı boş bırakılamaz.',
            'stock_quantity.integer' => 'Stok miktarı sayı olmalıdır.',
            'stock_quantity.min' => 'Stok miktarı en az 0 olmalıdır.',

            'images.array' => 'Resimler dizi olmalıdır.',
            'images.*.image' => 'Resimler resim dosyası olmalıdır.',
            'images.*.mimes' => 'Resimler jpeg, png, jpg, gif, svg formatında olmalıdır.',
            'images.*.max' => 'Resimler en fazla 2MB olmalıdır.',

            'variants.required' => 'En az bir varyant eklenmelidir.',
            'variants.array' => 'Variants dizi olmalıdır.',
            'variants.*.sku.required' => 'SKU boş bırakılamaz.',
            'variants.*.sku.unique' => 'SKU benzersiz olmalıdır.',
            'variants.*.price.required' => 'Fiyat boş bırakılamaz.',
            'variants.*.price.numeric' => 'Fiyat sayı olmalıdır.',
            'variants.*.price.min' => 'Fiyat en az 0 olmalıdır.',
            'variants.*.stock_quantity.required' => 'Stok boş bırakılamaz.',
            'variants.*.stock_quantity.integer' => 'Stok sayı olmalıdır.',
            'variants.*.stock_quantity.min' => 'Stok en az 0 olmalıdır.',
            'variants.*.images.required' => 'Varyant resimleri boş bırakılamaz.',
            'variants.*.images.array' => 'Varyant resimleri dizi olmalıdır.',
            'variants.*.images.*.image' => 'Varyant resimleri dosya olmalıdır.',
            'variants.*.images.*.mimes' => 'Varyant resimleri jpeg, png, jpg, gif, svg formatında olmalıdır.',
            'variants.*.images.*.max' => 'Varyant resimleri en fazla 2MB olmalıdır.',

            'variants.*.attributes.required' => 'Varyant için en az bir özellik eklenmelidir.',
            'variants.*.attributes.*.attribute_id.required' => 'Attribute ID zorunludur.',
            'variants.*.attributes.*.attribute_id.exists' => 'Geçersiz attribute.',
            'variants.*.attributes.*.option_id.exists' => 'Geçersiz attribute option.',
            'variants.*.attributes.*.value.string' => 'Attribute değeri metin olmalıdır.'
        ];
    }
}
