<?php
namespace Kuro\LaravelSms\Facade;

class Sms extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Sms';
    }
}