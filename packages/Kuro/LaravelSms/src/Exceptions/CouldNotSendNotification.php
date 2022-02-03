<?php

namespace Kuro\LaravelSms\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($message, $code)
    {
        return new static('Sms responded with an error:'.$message.' '.$code);
    }
}