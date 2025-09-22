<?php

namespace App\Http\Requests\Seller\Product;

use Illuminate\Foundation\Http\FormRequest;

class BulkProductStoreRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [    
            'products' => 'required|array|min:1',
            'products.*.title' => 'required|string|max:255', 
            'products.*.category_id' => 'nullable|exists:categories,id',
            'products.*.list_price' => 'required|numeric|min:0',
            'products.*.stock_quantity' => 'required|integer|min:0',
            'products.*.images' => 'required|array|min:1',
            'products.*.images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
    
    public function messages(): array
    {
        return [
            'products.required' => 'Ürün listesi boş bırakılamaz.',
            'products.array' => 'Ürün listesi dizi formatında olmalıdır.',
            'products.min' => 'En az bir ürün eklemelisiniz.',
            'products.*.title.required' => 'Ürün adı boş bırakılamaz.',
            'products.*.title.string' => 'Ürün adı metin olmalıdır.',
            'products.*.title.max' => 'Ürün adı en fazla 255 karakter olmalıdır.',
            'products.*.category_id.exists' => 'Geçersiz kategori.',
            'products.*.list_price.required' => 'Liste fiyatı boş bırakılamaz.',
            'products.*.list_price.numeric' => 'Liste fiyatı sayı olmalıdır.',
            'products.*.stock_quantity.required' => 'Stok miktarı boş bırakılamaz.',
            'products.*.stock_quantity.integer' => 'Stok miktarı sayı olmalıdır.',
            'products.*.stock_quantity.min' => 'Stok miktarı en az 0 olmalıdır.',
            'products.*.images.required' => 'Resimler dizisi boş bırakılamaz.',
            'products.*.images.array' => 'Resimler dizisi olmalıdır.',
            'products.*.images.min' => 'En az bir resim eklemelisiniz.',
            'products.*.images.*.image' => 'Resimler resim dosyası olmalıdır.',
            'products.*.images.*.mimes' => 'Resimler jpeg, png, jpg, gif, svg formatında olmalıdır.',
            'products.*.images.*.max' => 'Resimler en fazla 2MB olmalıdır.',
        ];
    }
}
