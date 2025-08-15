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
            'author' => 'required|string|max:255',
            'list_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Ürün adı boş bırakılamaz.',
            'title.string' => 'Ürün adı metin olmalıdır.',
            'title.max' => 'Ürün adı en fazla 255 karakter olmalıdır.',
            'category_id.exists' => 'Geçersiz kategori.',
            'author.required' => 'Yazar adı boş bırakılamaz.',
            'author.string' => 'Yazar adı metin olmalıdır.',
            'author.max' => 'Yazar adı en fazla 255 karakter olmalıdır.',
            'list_price.required' => 'Liste fiyatı boş bırakılamaz.',
            'list_price.numeric' => 'Liste fiyatı sayı olmalıdır.',
            'stock_quantity.required' => 'Stok miktarı boş bırakılamaz.',
            'stock_quantity.integer' => 'Stok miktarı sayı olmalıdır.',
            'stock_quantity.min' => 'Stok miktarı en az 0 olmalıdır.',
            'images.array' => 'Resimler dizisi olmalıdır.',
            'images.*.image' => 'Resimler resim dosyası olmalıdır.',
            'images.*.mimes' => 'Resimler jpeg, png, jpg, gif, svg formatında olmalıdır.',
            'images.*.max' => 'Resimler en fazla 2MB olmalıdır.',
        ];
    }
}
