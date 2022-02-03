<?php

namespace App\Providers;

use App\Services\ProductImageService;
use App\Shop\Facades\ProductImage as ProductImageFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Product image
        $loader = AliasLoader::getInstance();
        $loader->alias('productimage', ProductImageFacade::class);

        $this->app->singleton('productimage', function () {
            return app()->make(ProductImageService::class);
        });

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
