<?php

namespace App\Http\Requests\Seller\Product\Image;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class VariantImageReorderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1'],
            'images.*.id' => [
                'required',
                'integer',
                Rule::exists('product_variant_images', 'id')
            ],
            'images.*.sort_order' => ['required', 'integer', 'min:1'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $images = $this->input('images', []);

            $primaryCount = collect($images)->where('sort_order', 1)->count();

            if ($primaryCount > 1) {
                $validator->errors()->add(
                    'images',
                    'Sadece 1 resim sort_order=1 olabilir (primary resim).'
                );
            }

            $sortOrders = collect($images)->pluck('sort_order');
            if ($sortOrders->count() !== $sortOrders->unique()->count()) {
                $validator->errors()->add(
                    'images',
                    'Her resmin sort_order değeri benzersiz olmalı.'
                );
            }
        });
    }
}
