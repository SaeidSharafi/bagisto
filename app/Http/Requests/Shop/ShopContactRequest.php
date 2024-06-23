<?php

namespace App\Http\Requests\Shop;

use App\Rules\IranMobilePhoneRule;
use Illuminate\Foundation\Http\FormRequest;

class ShopContactRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:184'],
            'last_name'  => ['required', 'string', 'max:184'],
            'email'      => ['required', 'email', 'max:184'],
            'phone'      => ['required', new IranMobilePhoneRule()],
            'subject'    => ['required', 'string', 'max:184'],
            'message'    => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
