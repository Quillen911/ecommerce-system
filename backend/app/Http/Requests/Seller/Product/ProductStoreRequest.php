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
                $variants[$i]['is_popular'] = in_array($value, [true, 1, '1', 'true', 'on', 'yes'], true);
            }

            if (!empty($variant['sizes']) && is_array($variant['sizes'])) {
                foreach ($variant['sizes'] as $s => $size) {
                    if (!empty($size['inventory']) && isset($size['inventory'][0]) && is_array($size['inventory'][0])) {
                        $variants[$i]['sizes'][$s]['inventory'] = $size['inventory'][0];
                    }
                }
            }
        }

        $this->merge(['variants' => $variants]);
    }

    public function rules(): array
    {
        return [
            'title'            => 'required|string|max:255',
            'category_id'      => 'nullable|exists:categories,id',
            'description'      => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
            'meta_title'       => 'nullable|string|max:60',

            'variants'                     => 'required|array|min:1',
            'variants.*.color_name'        => 'required|string|max:255',
            'variants.*.color_code'        => 'nullable|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'variants.*.price_cents'       => 'required|integer|min:0',
            'variants.*.is_popular'        => 'sometimes|boolean',
            'variants.*.images'            => 'required|array|min:1',
            'variants.*.images.*'          => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'variants.*.sizes'                             => 'required|array|min:1',
            'variants.*.sizes.*.size_option_id'            => 'required|integer|min:0',
            'variants.*.sizes.*.price_cents'               => 'sometimes|integer|min:0',
            'variants.*.sizes.*.inventory.on_hand'         => 'required|integer|min:0',
            'variants.*.sizes.*.inventory.reserved'        => 'sometimes|integer|min:0',
            'variants.*.sizes.*.inventory.warehouse_id'    => 'sometimes|integer|min:0',
            'variants.*.sizes.*.inventory.min_stock_level' => 'sometimes|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Ürün adı boş bırakılamaz.',
            'title.string'   => 'Ürün adı metin olmalıdır.',
            'title.max'      => 'Ürün adı en fazla 255 karakter olmalıdır.',
            'category_id.exists' => 'Geçersiz kategori seçtiniz.',

            'variants.required' => 'En az bir varyant eklenmelidir.',
            'variants.array'    => 'Varyant bilgisi dizi olmalıdır.',

            'variants.*.color_name.required' => 'Varyant renk adı zorunludur.',
            'variants.*.color_name.string'   => 'Varyant renk adı metin olmalıdır.',
            'variants.*.color_name.max'      => 'Varyant renk adı en fazla 255 karakter olmalıdır.',

            'variants.*.color_code.size'  => 'Renk kodu # ve 6 karakterden oluşmalıdır.',
            'variants.*.color_code.regex' => 'Geçerli bir hex renk kodu giriniz.',

            'variants.*.price_cents.required' => 'Varyant fiyatı boş bırakılamaz.',
            'variants.*.price_cents.integer'  => 'Varyant fiyatı sayı olmalıdır.',
            'variants.*.price_cents.min'      => 'Varyant fiyatı en az 0 olmalıdır.',

            'variants.*.images.required' => 'Her varyant için en az bir görsel yüklenmelidir.',
            'variants.*.images.array'    => 'Görseller dizi formatında gönderilmelidir.',
            'variants.*.images.*.required' => 'Yüklenen her öğe bir dosya olmalıdır.',
            'variants.*.images.*.image'     => 'Görseller yalnızca resim formatında olmalıdır.',
            'variants.*.images.*.mimes'     => 'Görseller jpeg, png, jpg, gif veya svg formatında olmalıdır.',
            'variants.*.images.*.max'       => 'Görsel boyutu en fazla 2 MB olabilir.',

            'variants.*.is_popular.boolean' => 'Popülerlik bilgisi boolean olmalıdır.',

            'variants.*.sizes.required' => 'Varyant için en az bir beden eklenmelidir.',
            'variants.*.sizes.array'    => 'Varyant bedenleri dizi olmalıdır.',

            'variants.*.sizes.*.size_option_id.required' => 'Beden seçimi zorunludur.',
            'variants.*.sizes.*.size_option_id.integer'  => 'Beden ID sayı olmalıdır.',
            'variants.*.sizes.*.size_option_id.min'      => 'Beden ID en az 0 olmalıdır.',

            'variants.*.sizes.*.price_cents.integer' => 'Beden fiyatı sayı olmalıdır.',
            'variants.*.sizes.*.price_cents.min'     => 'Beden fiyatı en az 0 olmalıdır.',

            'variants.*.sizes.*.inventory.on_hand.required' => 'Stoktaki miktar zorunludur.',
            'variants.*.sizes.*.inventory.on_hand.integer'  => 'Stoktaki miktar sayı olmalıdır.',
            'variants.*.sizes.*.inventory.on_hand.min'      => 'Stoktaki miktar en az 0 olmalıdır.',

            'variants.*.sizes.*.inventory.reserved.integer'     => 'Rezerve miktarı sayı olmalıdır.',
            'variants.*.sizes.*.inventory.warehouse_id.integer' => 'Depo ID sayı olmalıdır.',
            'variants.*.sizes.*.inventory.min_stock_level.integer' => 'Minimum stok seviyesi sayı olmalıdır.',
            'variants.*.sizes.*.inventory.min_stock_level.min'     => 'Minimum stok seviyesi en az 0 olmalıdır.',
        ];
    }
}
