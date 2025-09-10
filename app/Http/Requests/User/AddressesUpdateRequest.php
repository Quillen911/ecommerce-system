<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AddressesUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:255',
            'address_line_1' => 'sometimes|string|max:255',
            'address_line_2' => 'sometimes|nullable|string|max:255',
            'district' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'postal_code' => 'sometimes|nullable|string|max:255',
            'country' => 'sometimes|string|max:255',
            'is_default' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'notes' => 'sometimes|nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'title.string' => 'Başlık metin olmalıdır.',
            'title.max' => 'Başlık en fazla 255 karakter olmalıdır.',
            'first_name.string' => 'Ad metin olmalıdır.',
            'first_name.max' => 'Ad en fazla 255 karakter olmalıdır.',
            'last_name.string' => 'Soyad metin olmalıdır.',
            'last_name.max' => 'Soyad en fazla 255 karakter olmalıdır.',
            'phone.string' => 'Telefon metin olmalıdır.',
            'phone.max' => 'Telefon en fazla 255 karakter olmalıdır.',
            'address_line_1.string' => 'Adres metin olmalıdır.',
            'address_line_1.max' => 'Adres en fazla 255 karakter olmalıdır.',
            'address_line_2.string' => 'Adres 2 metin olmalıdır.',
            'address_line_2.max' => 'Adres 2 en fazla 255 karakter olmalıdır.',
            'district.string' => 'İlçe metin olmalıdır.',
            'district.max' => 'İlçe en fazla 255 karakter olmalıdır.',
            'city.string' => 'Şehir metin olmalıdır.',
            'city.max' => 'Şehir en fazla 255 karakter olmalıdır.',
            'postal_code.string' => 'Posta kodu metin olmalıdır.',
            'postal_code.max' => 'Posta kodu en fazla 255 karakter olmalıdır.',
            'country.string' => 'Ülke metin olmalıdır.',
            'country.max' => 'Ülke en fazla 255 karakter olmalıdır.',
            'is_default.boolean' => 'Varsayılan adres booleansal değer olmalıdır.',
            'is_active.boolean' => 'Aktif adres booleansal değer olmalıdır.',
            'notes.string' => 'Notlar metin olmalıdır.',
            'notes.max' => 'Notlar en fazla 255 karakter olmalıdır.',
        ];
    }
}
