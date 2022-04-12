<?php

namespace App\Providers;


use App\Shop\Facades\Cart;
use App\Shop\Facades\JeduCoreFacade;
use App\Shop\JeduCore;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Webkul\Category\Repositories\CategoryRepository;

class ShopCoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('jeducore', JeduCoreFacade::class);

        $this->app->singleton('jeducore', function () {
            return app()->make(JeduCore::class);
        });


    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/helper.php';
        if(!app()->runningInConsole()) {

            view()->share('direction', core()->getCurrentLocale()->direction);
            view()->composer('*', function ($view) {
                $categories = app(CategoryRepository::class)->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);
                $view->with('categories',$categories);
            });

        }
    }
}
