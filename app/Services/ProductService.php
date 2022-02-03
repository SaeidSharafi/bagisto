<?php

namespace App\Services;



use App\Shop\Facades\ProductImage;

class ProductService
{
    /**
     * Format product.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  bool  $list
     * @param  array  $metaInformation
     *
     * @return array
     */
    public static function formatProduct(
        $product,
        $list = false,
        $metaInformation = []
    ) {

        $galleryImages = ProductImage::getGalleryImages($product);
        $productImage = ProductImage::getProductBaseImage($product,
            $galleryImages)['medium_image_url'];

        $largeProductImageName = "large-product-placeholder.jpg";
        $mediumProductImageName = "meduim-product-placeholder.png";

        if (strpos($productImage, $mediumProductImageName) > -1) {
            $productImageNameCollection = explode('/', $productImage);
            $productImageName
                = $productImageNameCollection[sizeof($productImageNameCollection)
            - 1];

            if ($productImageName == $mediumProductImageName) {
                $productImage = str_replace($mediumProductImageName,
                    $largeProductImageName, $productImage);
            }
        }

        $priceHTML = view('shop::products.price',
            ['product' => $product])->render();

        $isProductNew = ($product->new
            && !(strpos($priceHTML, 'sticker sale') > 0))
            ? __('shop::app.products.new') : false;

        return [
            'priceHTML'        => $priceHTML,
            'image'            => $productImage,
            'new'              => $isProductNew,
            'galleryImages'    => $galleryImages,
            'name'             => $product->name,
            'slug'             => $product->url_key,
            'description'      => $product->description,
            'shortDescription' => $product->short_description,
            'category_slug' => $product->category_slug,
            'category_name' => $product->category_name,
        ];
    }
}