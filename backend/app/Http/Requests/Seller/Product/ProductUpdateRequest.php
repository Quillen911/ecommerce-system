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
            'category_id' => 'sometimes|exists:categories,id',
            'description' => 'sometimes|string',
            'meta_description' => 'sometimes|string|max:160',
            'meta_title' => 'sometimes|string|max:60',

           /* 'variants' => 'sometimes|array|min:1',
            'variants.*.id' => 'required|integer|exists:product_variants,id',
            'variants.*.color_name' => 'sometimes|string|max:255',
            'variants.*.color_code' => 'sometimes|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'variants.*.price_cents' => 'sometimes|integer|min:0',
            'variants.*.images' => 'sometimes|array',
            'variants.*.images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'variants.*.is_popular' => 'sometimes|boolean',
            'variants.*.is_active' => 'sometimes|boolean',

            'variants.*.sizes' => 'sometimes|array|min:1',
            'variants.*.sizes.*.size_option_id' => 'sometimes|integer|min:0',
            'variants.*.sizes.*.price_cents' => 'sometimes|integer|min:0',

            'variants.*.sizes.*.inventory' => 'sometimes|array|min:1',
            'variants.*.sizes.*.inventory.*.on_hand' => 'sometimes|integer|min:0',
            'variants.*.sizes.*.inventory.*.reserved' => 'sometimes|integer|min:0',
            'variants.*.sizes.*.inventory.*.warehouse_id' => 'sometimes|integer|min:0',*/
        ];
    }


public function messages(): array
{
    return [
        'title.string' => 'Ürün adı metin olmalıdır.',
        'title.max' => 'Ürün adı en fazla 255 karakter olmalıdır.',
        'category_id.exists' => 'Geçersiz kategori.',

        'meta_title.string' => 'Meta title metin olmalıdır.',
        'meta_title.max' => 'Meta title en fazla 60 karakter olmalıdır.',
        'meta_description.string' => 'Meta description metin olmalıdır.',
        'meta_description.max' => 'Meta description en fazla 160 karakter olmalıdır.',

        'is_published.boolean' => 'Yayın durumu boolean olmalıdır.',

        'variants.array' => 'Variants dizi olmalıdır.',
        'variants.*.id.exists' => 'Geçersiz varyant.',
        'variants.*.color_name.string' => 'Varyant renk adı metin olmalıdır.',
        'variants.*.color_name.max' => 'Varyant renk adı en fazla 255 karakter olmalıdır.',
        'variants.*.color_code.string' => 'Varyant renk kodu metin olmalıdır.',
        'variants.*.color_code.size' => 'Varyant renk kodu 7 karakter olmalıdır (örn: #000000).',
        'variants.*.color_code.regex' => 'Varyant renk kodu geçerli bir HEX formatında olmalıdır (örn: #00FF00).',
        'variants.*.price_cents.integer' => 'Fiyat sayı olmalıdır.',
        'variants.*.price_cents.min' => 'Fiyat en az 0 olmalıdır.',
        'variants.*.images.array' => 'Varyant resimleri dizi olmalıdır.',
        'variants.*.images.*.image' => 'Varyant resimleri dosya olmalıdır.',
        'variants.*.images.*.mimes' => 'Varyant resimleri jpeg, png, jpg, gif, svg formatında olmalıdır.',
        'variants.*.images.*.max' => 'Varyant resimleri en fazla 2MB olmalıdır.',
        'variants.*.is_popular.boolean' => 'Varyant popülerlik boolean olmalıdır.',
        'variants.*.is_active.boolean' => 'Varyant aktiflik boolean olmalıdır.',
        'variants.*.sizes.array' => 'Varyant bedenleri dizi olmalıdır.',
        'variants.*.sizes.*.size_option_id.integer' => 'Beden ID sayı olmalıdır.',
        'variants.*.sizes.*.size_option_id.min' => 'Beden ID en az 0 olmalıdır.',
        'variants.*.sizes.*.price_cents.integer' => 'Beden fiyatı sayı olmalıdır.',
        'variants.*.sizes.*.price_cents.min' => 'Beden fiyatı en az 0 olmalıdır.',
        'variants.*.sizes.*.inventory.*.on_hand.integer' => 'Stokta bulunan miktar sayı olmalıdır.',
        'variants.*.sizes.*.inventory.*.on_hand.min' => 'Stokta bulunan miktar en az 0 olmalıdır.',
        'variants.*.sizes.*.inventory.*.reserved.integer' => 'Rezerve miktarı sayı olmalıdır.',
        'variants.*.sizes.*.inventory.*.warehouse_id.integer' => 'Depo ID sayı olmalıdır.',
        'variants.*.sizes.*.inventory.*.warehouse_id.min' => 'Depo ID en az 0 olmalıdır.',
    ];
}
}
