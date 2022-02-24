<?php

namespace App\Listeners;

use App\Repositories\JeduProductRepository;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Repositories\ProductFlatRepository;

class Product
{

    /**
     * ProductRepository object
     *
     * @var JeduProductRepository
     */
    protected $productRepository;

    /**
     * CategoryRepository object
     *
     * @var ProductFlatRepository
     */
    protected $productFlatRepository;

    /**
     * Create a new helper instance.
     *
     * @param  JeduProductRepository  $productRepository
     * @return void
     */
    public function __construct(JeduProductRepository $productRepository, ProductFlatRepository $productFlatRepository)
    {
        $this->productRepository =  $productRepository;
        $this->productFlatRepository =  $productFlatRepository;
    }

    /**
     * @param  \Webkul\Category\Contracts\Product  $product
     * @return \Webkul\Category\Contracts\Product
     */
    public function storeProductBanner($product)
    {
        $data = request()->all();


        if (! $product instanceof \Webkul\Product\Contracts\Product) {
            $product = $this->productRepository->findOrFail($product);
        }

        $product = $this->uploadImage($product, $data, 'banner');

        return $product;
    }
    /**
     * @param  \Illuminate\Database\Eloquent\Model  $slider
     * @param  array  $data
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function uploadImage($model, $data, $type) {

        $product_flat = $this->productFlatRepository->findOneWhere(
            ['product_id' => $model->id,
                'channel' => core()->getCurrentChannel()->code,
                'locale' => core()->getCurrentLocale()->code]
        );

        if (isset($data[$type])) {
            $request = request();
            foreach ($data[$type] as $imageId => $image) {
                $file = $type . '.' . $imageId;
                $dir = 'velocity/' . $type . '/' . $product_flat->id;

                if ($request->hasFile($file)) {
                    if ($product_flat->{$type}) {
                        Storage::delete($product_flat->{$type});
                    }

                    $product_flat->{$type} = $request->file($file)->store($dir);
                    $product_flat->save();
                }
            }
        } else {
            if ($product_flat->{$type}) {
                Storage::delete($product_flat->{$type});
            }

            $product_flat->{$type} = null;
            $product_flat->save();
        }

        return $model;
    }
}