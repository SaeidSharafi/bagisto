<?php

namespace App\Requests\Shop;

use Webkul\Customer\Facades\Captcha;

class JeduCustomerRegistrationRequest
    extends \Illuminate\Foundation\Http\FormRequest
{
    /**
     * Define your rules.
     *
     * @var array
     */
    private $rules = [
        'phone' => 'string|required|regex:/09[0-9]{9}/',
    ];

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
        return Captcha::getValidations($this->rules);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return Captcha::getValidationMessages();
    }
}