<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Velocity\Repositories\Product\ProductRepository;

class JeduProductRepository extends ProductRepository
{
    /**
     * Returns newly added product
     *
     * @param  int  $count
     *
     * @return \Illuminate\Support\Collection
     */
    public function getNewProducts($count)
    {
        $results = Cache::rememberForever('new_products',
            static function () use ($count) {
                return app(ProductFlatRepository::class)->with(['product'])
                    ->scopeQuery(function ($query) {
                        $channel = core()->getRequestedChannelCode();
                        $locale = core()->getRequestedLocaleCode();

                        return $query->distinct()
                            ->addSelect('product_flat.*')
                            ->selectRaw('category_translations.slug as category_slug')
                            ->selectRaw('category_translations.name as category_name')
                            ->leftJoin(DB::raw('(SELECT * FROM product_categories GROUP BY product_id) as product_categories'),function ($join){
                                $join->on(
                                    'product_categories.product_id', '=',
                                    'product_flat.product_id');
                            })
                            ->leftJoin('category_translations',
                                function ($join) use ($locale) {
                                    $join->on(
                                        'product_categories.category_id', '=',
                                        'category_translations.category_id')
                                        ->where('category_translations.locale',
                                            '=',
                                            $locale);
                                })
                            ->where('product_flat.status', 1)
                            ->where('product_flat.visible_individually', 1)
                            ->where('product_flat.new', 1)
                            ->where('product_flat.channel', $channel)
                            ->where('product_flat.locale', $locale)
                            ->orderBy('product_flat.product_id', 'desc');

                    })->paginate($count);
            });
        return $results;
    }

    public function getFeaturedProducts($count)
    {
        $results = Cache::rememberForever('featured_products',
            static function () use ($count) {
                return app(ProductFlatRepository::class)->with(['product'])
                    ->scopeQuery(function ($query) {
                        $channel = core()->getRequestedChannelCode();

                        $locale = core()->getRequestedLocaleCode();

                        return $query->distinct()
                            ->addSelect('product_flat.*')
                            ->selectRaw('category_translations.slug as category_slug')
                            ->selectRaw('category_translations.name as category_name')
                            ->leftJoin(DB::raw('(SELECT * FROM product_categories GROUP BY product_id) as product_categories'),function ($join){
                                $join->on(
                                    'product_categories.product_id', '=',
                                    'product_flat.product_id');
                            })
                            ->leftJoin('category_translations',
                                function ($join) use ($locale) {
                                    $join->on(
                                        'product_categories.category_id', '=',
                                        'category_translations.category_id')
                                        ->where('category_translations.locale',
                                            '=',
                                            $locale);
                                })
                            ->where('product_flat.status', 1)
                            ->where('product_flat.visible_individually', 1)
                            ->where('product_flat.featured', 1)
                            ->where('product_flat.channel', $channel)
                            ->where('product_flat.locale', $locale)
                            ->orderBy('product_id', 'desc');
                    })->paginate($count);
            });
        return $results;
    }

    public function getFreeProducts($count)
    {
        Cache::forget('free_products');
        $results = Cache::rememberForever('free_products',
            static function () use ($count) {
                return app(ProductFlatRepository::class)->with(['product'])
                    ->scopeQuery(function ($query) {
                        $channel = core()->getRequestedChannelCode();

                        $locale = core()->getRequestedLocaleCode();

                        return $query->distinct()
                            ->addSelect('product_flat.*')
                            ->selectRaw('category_translations.slug as category_slug')
                            ->selectRaw('category_translations.name as category_name')
                            ->leftJoin(DB::raw('(SELECT * FROM product_categories GROUP BY product_id) as product_categories'),function ($join){
                                $join->on(
                                    'product_categories.product_id', '=',
                                    'product_flat.product_id');
                            })
                            ->leftJoin('category_translations',
                                function ($join) use ($locale) {
                                    $join->on(
                                        'product_categories.category_id', '=',
                                        'category_translations.category_id')
                                        ->where('category_translations.locale',
                                            '=',
                                            $locale);
                                })
                            ->where('product_flat.status', 1)
                            ->where('product_flat.visible_individually', 1)
                            ->where('parent_id',null)
                            ->where(function ($query){
                                $query->where('product_flat.price', 0)
                                    ->orWhere('product_flat.min_price', 0);
                            })
                            ->where('product_flat.channel', $channel)
                            ->where('product_flat.locale', $locale)
                            ->orderBy('product_id', 'desc');
                    })->paginate($count);
            });
        return $results;
    }
}