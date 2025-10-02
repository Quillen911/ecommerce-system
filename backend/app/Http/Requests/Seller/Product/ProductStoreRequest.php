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
            'description' => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',

            // Varyantlar
            'variants' => 'required|array|min:1',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.images' => 'required|array',
            'variants.*.images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'variants.*.is_popular' => 'sometimes|boolean',

            // Varyant attribute validasyonu
            'variants.*.attributes' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    $attributeIds = collect($value)->pluck('attribute_id');
                    if ($attributeIds->count() !== $attributeIds->unique()->count()) {
                        $fail('Her attribute bir varyantta yalnızca bir kez seçilebilir.');
                    }
                }
            ],
            'variants.*.attributes.*.attribute_id' => 'required|exists:attributes,id',
            'variants.*.attributes.*.option_id' => 'required|exists:attribute_options,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Ürün adı boş bırakılamaz.',
            'title.string' => 'Ürün adı metin olmalıdır.',
            'title.max' => 'Ürün adı en fazla 255 karakter olmalıdır.',
            'category_id.exists' => 'Geçersiz kategori.',

            'stock_quantity.required' => 'Stok miktarı boş bırakılamaz.',
            'stock_quantity.integer' => 'Stok miktarı sayı olmalıdır.',
            'stock_quantity.min' => 'Stok miktarı en az 0 olmalıdır.',

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
            'variants.*.is_popular.boolean' => 'Varyant popülerlik boolean olmalıdır.',

            'variants.*.attributes.required' => 'Varyant için en az bir özellik eklenmelidir.',
            'variants.*.attributes.*.attribute_id.required' => 'Attribute ID zorunludur.',
            'variants.*.attributes.*.attribute_id.exists' => 'Geçersiz attribute.',
            'variants.*.attributes.*.option_id.required' => 'Attribute option zorunludur.',
            'variants.*.attributes.*.option_id.exists' => 'Geçersiz attribute option.',
        ];
    }
}
