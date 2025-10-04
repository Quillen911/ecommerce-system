<?php

namespace App\Http\Requests\Seller\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $variants = $this->input('variants', []);
    
        foreach ($variants as $i => $variant) {
            if (array_key_exists('is_popular', $variant)) {
                $value = $variant['is_popular'];
    
                // Normalize
                if (in_array($value, [true, 1, "1", "true", "on", "yes"], true)) {
                    $variants[$i]['is_popular'] = true;
                } else {
                    $variants[$i]['is_popular'] = false;
                }
            }
        }
    
        $this->merge(['variants' => $variants]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
            'meta_title' => 'nullable|string|max:60',
            
            'variants' => 'required|array|min:1',
            'variants.*.color_name' => 'required|string|max:255',
            'variants.*.color_code' => 'nullable|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'variants.*.price_cents' => 'required|integer|min:0',
            'variants.*.images' => 'required|array',
            'variants.*.images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'variants.*.is_popular' => 'sometimes|boolean',
            'variants.*.sizes' => 'required|array|min:1',
            'variants.*.sizes.*.size_option_id' => 'required|integer|min:0',
            'variants.*.sizes.*.price_cents' => 'sometimes|integer|min:0',
            'variants.*.sizes.*.inventory.*.on_hand' => 'required|integer|min:0',
            'variants.*.sizes.*.inventory.*.reserved' => 'sometimes|integer|min:0',
            'variants.*.sizes.*.inventory.*.warehouse_id' => 'sometimes|integer|min:0',

        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Ürün adı boş bırakılamaz.',
            'title.string' => 'Ürün adı metin olmalıdır.',
            'title.max' => 'Ürün adı en fazla 255 karakter olmalıdır.',
            'category_id.exists' => 'Geçersiz kategori.',


            'variants.required' => 'En az bir varyant eklenmelidir.',
            'variants.array' => 'Variants dizi olmalıdır.',
            'variants.*.price_cents.required' => 'Fiyat boş bırakılamaz.',
            'variants.*.price_cents.integer' => 'Fiyat sayı olmalıdır.',
            'variants.*.price_cents.min' => 'Fiyat en az 0 olmalıdır.',
            'variants.*.color_name.required' => 'Varyant renk adı zorunludur.',
            'variants.*.color_name.string' => 'Varyant renk adı metin olmalıdır.',
            'variants.*.color_name.max' => 'Varyant renk adı en fazla 255 karakter olmalıdır.',
            'variants.*.is_popular.boolean' => 'Boolean olmalıdır.',

            'variants.*.sizes.required' => 'Varyant için en az bir beden eklenmelidir.',
            'variants.*.sizes.array' => 'Varyant bedenleri dizi olmalıdır.',
            'variants.*.sizes.*.size_option_id.required' => 'Beden seçimi zorunludur.',
            'variants.*.sizes.*.size_option_id.integer' => 'Beden ID sayı olmalıdır.',
            'variants.*.sizes.*.size_option_id.min' => 'Beden ID en az 0 olmalıdır.',
            'variants.*.sizes.*.price_cents.integer' => 'Beden fiyatı sayı olmalıdır.',
            'variants.*.sizes.*.price_cents.min' => 'Beden fiyatı en az 0 olmalıdır.',
            'variants.*.sizes.*.inventory.*.on_hand.required' => 'Stokta bulunan miktar zorunludur.',
            'variants.*.sizes.*.inventory.*.on_hand.integer' => 'Stokta bulunan miktar sayı olmalıdır.',
            'variants.*.sizes.*.inventory.*.on_hand.min' => 'Stokta bulunan miktar en az 0 olmalıdır.',
            'variants.*.sizes.*.inventory.*.reserved.integer' => 'Rezerve miktarı sayı olmalıdır.',
            'variants.*.sizes.*.inventory.*.warehouse_id.integer' => 'Depo ID sayı olmalıdır.',
        ];
    }
}
