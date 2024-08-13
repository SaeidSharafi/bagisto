<?php

use App\Services\ProductImageService;
use Webkul\Product\ProductVideo;
if (! function_exists('core')) {
    /**
     * Core helper.
     *
     * @return JeduCore
     */
    function core()
    {
        return app()->make(App\Shop\JeduCore::class);
    }
}
if (! function_exists('productimage')) {
    function productimage() {
        return app()->make(ProductImageService::class);
    }
}

if (! function_exists('productvideo')) {
    function productvideo() {
        return app()->make(ProductVideo::class);
    }
}

if (! function_exists('rial_to_toman')) {
    function rial_to_toman($price)
    {
        if ($price > 0) {
            $price = floor($price / 10);
        }
        return $price;
    }
}