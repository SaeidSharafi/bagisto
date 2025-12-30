<?php

declare(strict_types=1);

namespace DigipayGateway\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric'],
            'providerId' => ['required', 'string'],
            'trackingCode' => ['required', 'string'],
            'result' => ['required', 'string', 'in:SUCCESS,FAILURE'],
            'type' => ['nullable', 'numeric'],
            'rrn' => ['nullable', 'string'],
            'psp' => ['nullable', 'string'],
            'pspCode' => ['nullable', 'string'],
            'pspName' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'amount.required' => __('digipay::messages.validation.amount_required'),
            'providerId.required' => __('digipay::messages.validation.provider_id_required'),
            'trackingCode.required' => __('digipay::messages.validation.tracking_code_required'),
            'result.required' => __('digipay::messages.validation.result_required'),
        ];
    }
}
