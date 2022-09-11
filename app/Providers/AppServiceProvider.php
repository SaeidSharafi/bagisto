<?php

namespace App\Providers;


use App\Models\Shop\JeduCustomer;
use App\Models\Shop\JeduSlider;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        include_once __DIR__ . '/../Helpers/jdf.php';

        Carbon::macro('jdate', function ($format="", $tr_num = 'fa') {
            if (app()->getLocale() ==="fa"){
                $format= $format ?: 'j F Y H:i:s';
                return jdate($format, self::this()->timestamp, '', '', $tr_num);
            }
            $format= $format ?: 'Y-m-d';
            return self::this()->format($format);
        });

        Carbon::macro('jmktime', function ($year, $month, $day, $hour = 0, $minute = 0, $second = 0) {
            $timestamp = jmktime($hour, $minute, $second, $month, $day, $year);
            return self::createFromTimestamp($timestamp);
        });
        Carbon::macro('tojdate', function ($year, $month, $day) {
            $timestamp = gregorian_to_jalali($year, $month, $day);
            return self::createFromTimestamp($timestamp);
        });

        //Model::preventLazyLoading(! app()->isProduction());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //$this->mergeConfigFrom(
        //    dirname(__DIR__).'/../config/system.php', 'core'
        //);

        $this->mergeConfigFrom(
            dirname(__DIR__).'/../config/product_types.php', 'product_types'
        );
        $this->mergeConfigFrom(
            dirname(__DIR__).'/../config/sms.php', 'sms'
        );
        $this->app->concord->registerModel(
           \Webkul\Customer\Models\Customer::class, JeduCustomer::class
        );
        $this->app->concord->registerModel(
            \Webkul\Core\Models\Slider::class, JeduSlider::class
        );

    }
}
