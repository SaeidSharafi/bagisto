<?php

namespace Kuro\LaravelSms\Exceptions;

class CouldNotUseNotification extends \Exception
{
    public static function missingMethod()
    {
        return new static('Your notification does not have the toSms method. Please create.');
    }

    public static function missingConfig()
    {
        return new static('Gateway username and / or password are missing. Did you add it to service array and check your config file?');
    }
}