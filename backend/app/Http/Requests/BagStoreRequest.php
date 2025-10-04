<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BagStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'variant_size_id' => 'required|exists:variant_sizes,id',
            'quantity' => 'sometimes|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'variant_size_id.required' => 'Variant size zorunlu!',
            'variant_size_id.exists' => 'Variant size bulunamadı!',
            'quantity.integer' => 'Ürün adedi sayısal değer olmalıdır!',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Geçersiz istek.',
            'errors' => $validator->errors(),
        ], 422));
    }
}