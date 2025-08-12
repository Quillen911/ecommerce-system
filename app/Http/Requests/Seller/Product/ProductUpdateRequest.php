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
            'author' => 'sometimes|string|max:255',
            'list_price' => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            'title.string' => 'Ürün adı metin olmalıdır.',
            'title.max' => 'Ürün adı en fazla 255 karakter olmalıdır.',
            'category_id.exists' => 'Geçersiz kategori.',
            'author.string' => 'Yazar adı metin olmalıdır.',
            'author.max' => 'Yazar adı en fazla 255 karakter olmalıdır.',
            'list_price.numeric' => 'Liste fiyatı sayı olmalıdır.',
            'stock_quantity.numeric' => 'Stok miktarı sayı olmalıdır.',
            'stock_quantity.integer' => 'Stok miktarı sayı olmalıdır.',
            'stock_quantity.min' => 'Stok miktarı en az 0 olmalıdır.',
        ];
    }
}
