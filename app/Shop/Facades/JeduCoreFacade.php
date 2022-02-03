<?php

namespace App\Shop\Facades;

use Illuminate\Support\Facades\Facade;

class JeduCoreFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'jeducore';
    }
}