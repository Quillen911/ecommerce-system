<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [ 'Bag_User_id' => 'required|exists:users,id' ];
    }

    public function messages(): array
    {
        return [
            'Bag_User_id.required' => 'Kullanıcı ID zorunludur.',
            'Bag_User_id.exists' => 'Geçersiz kullanıcı ID.',
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