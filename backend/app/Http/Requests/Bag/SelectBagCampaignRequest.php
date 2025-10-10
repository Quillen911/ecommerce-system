<?php

namespace App\Http\Requests\Bag;

use Illuminate\Foundation\Http\FormRequest;

class SelectBagCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'integer', 'exists:campaigns,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'campaign_id.required' => 'Bir kampanya seçmelisiniz.',
            'campaign_id.exists'   => 'Seçtiğiniz kampanya bulunamadı.',
        ];
    }
}
