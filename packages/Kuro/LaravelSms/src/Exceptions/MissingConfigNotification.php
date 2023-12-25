<?php

namespace Kuro\LaravelSms\Exceptions;

class MissingConfigNotification extends \Exception
{
    public static function missingConfig()
    {
        return new static('Gateway username and / or password are missing. Did you add it to service array and check your .env file?');
    }
}