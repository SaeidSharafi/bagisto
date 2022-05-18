<?php

namespace App\Http\Controllers\Shop\API;

use App\Repositories\JeduProductRepository;
use App\Services\ProductService;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\SearchRepository;
use Webkul\Velocity\Helpers\Helper;
use Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository as CustomerCompareProductRepository;

class JeduShopController
    extends \Webkul\Velocity\Http\Controllers\Shop\ShopController
{
    public function __construct(
        Helper $velocityHelper,
        SearchRepository $searchRepository,
        ProductRepository $productRepository,
        WishlistRepository $wishlistRepository,
        CategoryRepository $categoryRepository,
        JeduProductRepository $velocityProductRepository,
        CustomerCompareProductRepository $compareProductsRepository
    ) {
        parent::__construct($velocityHelper, $searchRepository,
            $productRepository, $wishlistRepository, $categoryRepository,
            $velocityProductRepository, $compareProductsRepository);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function categoryDetails()
    {
        $slug = request()->get('category-slug');
        if (! $slug) {
            abort(404);
        }

        switch ($slug) {
            case 'new-products':
            case 'featured-products':
            case 'free-products':
                $count = request()->get('count');

                if ($slug == "new-products") {
                    $products = $this->velocityProductRepository->getNewProducts($count);
                } else if ($slug == "featured-products") {
                    $products = $this->velocityProductRepository->getFeaturedProducts($count);
                }else if ($slug == "free-products") {
                    $products = $this->velocityProductRepository->getFreeProducts($count);
                }

                $response = [
                    'status'   => true,
                    'products' => $products->map(function ($product) {
                        if (core()->getConfigData('catalog.products.homepage.out_of_stock_items')) {
                            return ProductService::formatProduct($product);
                        } else {
                            if ($product->isSaleable()) {
                                return ProductService::formatProduct($product);
                            }
                        }
                    })->reject(function ($product) {
                        return is_null($product);
                    })->values(),
                ];

                break;
            default:
                $categoryDetails = $this->categoryRepository->findByPath($slug);

                if ($categoryDetails) {
                    $list = false;
                    $customizedProducts = [];
                    $products = $this->productRepository->getAll($categoryDetails->id);

                    foreach ($products as $product) {
                        $productDetails = [];

                        $productDetails = array_merge($productDetails, ProductService::formatProduct($product));

                        array_push($customizedProducts, $productDetails);
                    }

                    $response = [
                        'status'           => true,
                        'list'             => $list,
                        'categoryDetails'  => $categoryDetails,
                        'categoryProducts' => $customizedProducts,
                    ];
                }

                break;
        }

        return $response ?? [
                'status' => false,
            ];
    }

    /**
     * This method will fetch products from category.
     *
     * @param  int  $categoryId
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategoryProducts($categoryId)
    {
        /* fetch category details */
        $categoryDetails = $this->categoryRepository->find($categoryId);

        /* if category not found then return empty response */
        if (! $categoryDetails) {
            return response()->json([
                'products'       => [],
                'paginationHTML' => '',
            ]);
        }

        /* fetching products */
        $products = $this->productRepository->getAll($categoryId);
        $products->withPath($categoryDetails->slug);

        /* sending response */
        return response()->json([
            'products'       => collect($products->items())->map(function ($product) {
                return ProductService::formatProduct($product);
            }),
            'paginationHTML' => $products->appends(request()->input())->links()->toHtml(),
        ]);
    }

}