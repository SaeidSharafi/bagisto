<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class IranMobilePhoneRule implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        if (preg_match("/^0?9[0-1-2-3-9]\d{8}$/", $value)) {
            return true;
        }

        if (preg_match("/^0[1-8]\d{9}$/", $value)) {
            return true;
        }
        return false;
    }
    public function message(): string
    {
        return __('validation.iran_mobile_phone');
    }
}
