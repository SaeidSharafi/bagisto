<?php

namespace MellatGateway\Exceptions;

class BaseException extends \Exception
{


    public $orderId;


    public function getErrorMessage()
    {
        switch ($this->code) {
            case 17:
                return 'انصراف از تراکنش توسط کاربر';
            case 31:
                return 'پاسخ دریافتی از بانک نامعتبر میباشد';
            case 41:
                return 'تراکنش تکراری میباشد.';
        }
        return '';
    }
}