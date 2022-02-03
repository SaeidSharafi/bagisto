<?php

namespace App\Providers;

use App\Shop\Cart;
use App\Shop\Facades\Cart as CartFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class CheckoutServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('cart', CartFacade::class);

        $this->app->singleton('cart', function () {
            return new cart();
        });

        $this->app->bind('cart', Cart::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
