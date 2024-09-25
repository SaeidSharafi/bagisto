<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Webkul\Product\Repositories\ProductRepository;

class GenerateSitemapCommand extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Command description';

    public function handle(): void
    {
        $updated_at = Carbon::parse('2024-09-25');
        $productRepository = app(ProductRepository::class);
        $product = $productRepository->latest('updated_at')->first();

        $sitemap = Sitemap::create();

        $sitemap->add(
            Url::create('/')
                ->setLastModificationDate($product->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );

        $pages = [
            route('shop.aboutus'),
            route('shop.contactus.view'),
            route('shop.cms.page', 'privacy-policy'),
            route('shop.cms.page', 'terms-and-conditions'),
        ];

        foreach ($pages as $page) {
            $sitemap->add(
                Url::create($page)
                    ->setLastModificationDate($updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.8)
            );
        }

        $categoryRepository = app(\Webkul\Category\Repositories\CategoryRepository::class);
        $categories = $categoryRepository->getCategoryTree();
        foreach ($categories->first()->children as $key => $category) {
            $sitemap->add(
                Url::create('/'.$category->url_key)
                    ->setLastModificationDate($category->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.7)
            );

        }
        $productRepository
            ->getModel()
            ->select('products.*')
            ->join('product_flat', 'product_flat.product_id', '=', 'products.id')
            ->where('status', 1)
            ->where('visible_individually', 1)
            ->whereNull('products.parent_id')
            ->chunk(100, function ($products) use (&$sitemap) {
                foreach ($products as $product) {
                    $sitemap->add(
                        Url::create('/'.$product->url_key)
                            ->setLastModificationDate($product->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                            ->setPriority(0.6)
                    );
                }

            });

        $sitemap->writeToFile(base_path().'/public/sitemap.xml');

    }
}
