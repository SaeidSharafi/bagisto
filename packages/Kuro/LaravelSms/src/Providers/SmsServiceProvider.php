<?php

namespace Kuro\LaravelSms\Providers;

use Illuminate\Support\ServiceProvider;
use Kuro\LaravelSms\Sms;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/sms.php', 'sms'
        );

        $this->app->bind('Sms', function(){
            return new Sms();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../Config/sms.php' => config_path('sms.php'),
        ]);

    }
}
