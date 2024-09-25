<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Nationalcode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!preg_match('/^\d{8}$|^\d{10}$|^\d{12}$|^\d{14}$/', $value)) {
            return false;
        }
        for ($i = 0; $i < 10; $i++) {
            if (preg_match('/^'.$i.'{8}$|^'.$i.'{10}$|^'.$i.'{12}$|^'.$i.'{14}$/', $value)) {
                return false;
            }
        }
        if (mb_strlen($value) > 10 || 8 === mb_strlen($value)) {
            return true;
        }

        for ($i = 0, $sum = 0; $i < 9; $i++) {
            $sum += ((10 - $i) * (int) substr($value, $i, 1));
        }
        $ret = $sum % 11;
        $parity = (int) substr($value, 9, 1);
        if (($ret < 2 && $ret === $parity) || ($ret >= 2 && $ret === 11 - $parity)) {
            return true;
        }
        return false;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'کد ملی اشتباه است';
    }
}
