<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePaymentIntentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Auth middleware zaten devrede
    }

    public function rules(): array
    {
        return [
            'session_id'        => ['required', 'uuid'],
            'payment_method'    => ['required', Rule::in(['saved_card', 'new_card', 'cash_on_delivery'])],
            'provider'          => ['required_if:payment_method,new_card', Rule::in(['iyzico'])],
            'payment_method_id' => ['required_if:payment_method,saved_card', 'nullable', 'integer'],
            'card_alias'        => ['required_if:payment_method,new_card', 'nullable', 'string', 'max:191'],
            'card_number'       => ['required_if:payment_method,new_card', 'nullable', 'digits_between:12,19'],
            'card_holder_name'  => ['required_if:payment_method,new_card', 'nullable', 'string', 'max:191'],
            'expire_month'      => ['required_if:payment_method,new_card', 'nullable', 'digits:2'],
            'expire_year'       => ['required_if:payment_method,new_card', 'nullable', 'digits:4'],
            'cvv'               => ['required_if:payment_method,new_card', 'nullable', 'digits_between:3,4'],
            'save_card'         => ['required_if:payment_method,new_card','nullable', 'boolean'],
            'installment'       => ['nullable', 'integer', 'min:1', 'max:12'],
            'requires_3ds'       => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'session_id.required'          => 'Checkout oturumu belirtilmelidir.',
            'payment_method.required'      => 'Ödeme yöntemi seçilmelidir.',
            'payment_method.in'            => 'Geçersiz ödeme yöntemi.',
            'payment_method_id.required_if'=> 'Kayıtlı kart seçtiğinizde kart ID’si zorunludur.',
            'card_number.required_if'      => 'Yeni kart için kart numarası zorunludur.',
            'card_holder_name.required_if'=> 'Yeni kart için kart sahibinin adı zorunludur.',
            'expire_month.required_if'    => 'Yeni kart için son kullanma ayı zorunludur.',
            'expire_year.required_if'     => 'Yeni kart için son kullanma yılı zorunludur.',
            'cvv.required_if'             => 'Yeni kart için CVV zorunludur.',
            'provider.required_if'        => 'Yeni kart için ödeme sağlayıcısı seçmelisiniz.',
            'provider.in'                 => 'Desteklenmeyen bir ödeme sağlayıcısı seçtiniz.',
            'save_card.required_if'       => 'Yeni kart için kart kaydetme seçeneğini belirlemelisiniz.',
        ];
    }
}
