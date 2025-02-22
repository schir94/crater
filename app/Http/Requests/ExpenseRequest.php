<?php

namespace Crater\Http\Requests;

use Crater\Models\CompanySetting;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $companyCurrency = CompanySetting::getSetting('currency', $this->header('company'));

        $rules = [
            'expense_date' => [
                'required',
            ],
            'expense_category_id' => [
                'required',
            ],
            'exchange_rate' => [
                'nullable'
            ],
            'payment_method_id' => [
                'nullable',
            ],
            'amount' => [
                'required',
            ],
            'customer_id' => [
                'nullable',
            ],
            'notes' => [
                'nullable',
            ],
            'currency_id' => [
                'required'
            ],
        ];

        if ($companyCurrency !== $this->currency_id) {
            $rules['exchange_rate'] = [
                'required',
            ];
        };

        return $rules;
    }

    public function getExpensePayload()
    {
        $company_currency = CompanySetting::getSetting('currency', $this->header('company'));
        $current_currency = $this->currency_id;
        $exchange_rate = $company_currency != $current_currency ? $this->exchange_rate : 1;

        return collect($this->validated())
            ->merge([
                'creator_id' => $this->user()->id,
                'company_id' => $this->header('company'),
                'exchange_rate' => $exchange_rate,
                'base_amount' => $this->amount * $exchange_rate,
                'currency_id' => $current_currency
            ])
            ->toArray();
    }
}
