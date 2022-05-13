<?php

namespace App\Providers;

use App\Auth\PasswordBrokerManager;

class PasswordResetServiceProvider extends \Illuminate\Auth\Passwords\PasswordResetServiceProvider
{
    protected function registerPasswordBroker()
    {
        $this->app->singleton('auth.password', function ($app) {
            // reference your new broker
            // the rest of the method code is unchanged
            return new PasswordBrokerManager($app);
        });
        $this->app->bind('auth.password.broker', function ($app) {
            return $app->make('auth.password')->broker();
        });
    }

}