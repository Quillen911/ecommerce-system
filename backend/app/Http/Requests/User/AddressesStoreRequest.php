<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AddressesStoreRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'district' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Başlık zorunludur.',
            'title.string' => 'Başlık metin olmalıdır.',
            'title.max' => 'Başlık en fazla 255 karakter olmalıdır.',
            'first_name.required' => 'Ad zorunludur.',
            'first_name.string' => 'Ad metin olmalıdır.',
            'first_name.max' => 'Ad en fazla 255 karakter olmalıdır.',
            'last_name.required' => 'Soyad zorunludur.',
            'last_name.string' => 'Soyad metin olmalıdır.',
            'last_name.max' => 'Soyad en fazla 255 karakter olmalıdır.',
            'phone.required' => 'Telefon zorunludur.',
            'phone.string' => 'Telefon metin olmalıdır.',
            'phone.max' => 'Telefon en fazla 255 karakter olmalıdır.',
            'address_line_1.required' => 'Adres zorunludur.',
            'address_line_1.string' => 'Adres metin olmalıdır.',
            'address_line_1.max' => 'Adres en fazla 255 karakter olmalıdır.',
            'address_line_2.string' => 'Adres 2 metin olmalıdır.',
            'address_line_2.max' => 'Adres 2 en fazla 255 karakter olmalıdır.',
            'district.required' => 'İlçe zorunludur.',
            'district.string' => 'İlçe metin olmalıdır.',
            'district.max' => 'İlçe en fazla 255 karakter olmalıdır.',
            'city.required' => 'Şehir zorunludur.',
            'city.string' => 'Şehir metin olmalıdır.',
            'city.max' => 'Şehir en fazla 255 karakter olmalıdır.',
            'postal_code.string' => 'Posta kodu metin olmalıdır.',
            'postal_code.max' => 'Posta kodu en fazla 255 karakter olmalıdır.',
            'country.required' => 'Ülke zorunludur.',
            'country.string' => 'Ülke metin olmalıdır.',
            'country.max' => 'Ülke en fazla 255 karakter olmalıdır.',
            'is_default.boolean' => 'Varsayılan adres booleansal değer olmalıdır.',
            'is_active.boolean' => 'Aktif adres booleansal değer olmalıdır.',
            'notes.string' => 'Notlar metin olmalıdır.',
            'notes.max' => 'Notlar en fazla 255 karakter olmalıdır.',
        ];
    }
}
