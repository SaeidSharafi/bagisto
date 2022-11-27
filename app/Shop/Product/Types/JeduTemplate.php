<?php

namespace App\Shop\Product\Types;

use App\Services\CatalogRuleProductPriceService;
use phpDocumentor\Reflection\Types\Mixed_;
use Webkul\Product\Type\Simple;

class JeduTemplate extends Simple
{

    protected $skipAttributes
        = [
            'product_number', 'url_key', 'visible_individually',
            'tax_category_id', 'new', 'featured', 'teacher_bio',
            'status', 'short_name', 'teacher_name', 'teacher_image','teacher_id'
            'meta_title', 'meta_keywords', 'meta_description', 'guest_checkout',
            'inventories', 'images', 'meta_description', 'banner',
            'videos', 'categories', 'channels', 'banner','variants',
            'price', 'cost', 'special_price', 'special_price_from',
            'special_price_to', 'length', 'width', 'height', 'weight'
        ];

    protected $additionalViews = [
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.videos',
    ];
    /**
     * Return validation rules.
     *
     * @return array
     */
    public function getTypeValidationRules()
    {
        return [
            'product_number'       => 'nullable',
            'url_key'              => 'nullable',
            'visible_individually' => 'nullable',
            'status'               => 'nullable',
            'short_name'           => 'nullable',
            'teacher_name'         => 'nullable',
            'price'                => 'nullable',
        ];
    }

    /**
     * Returns additional information for items.
     *
     * @param  array  $data
     *
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        $discount = $regular_price = 0;
        if ($this->haveSpecialPrice()) {
            if ($this->isPercentOffer()) {
                $discount = $this > getDiscountPercent();
            }
            $regular_price = $this->evaluatePrice($this->getMaximamPrice());
        }
        $data['discount_data'] = [
            'discount'      => $discount,
            'regular_price' => $regular_price,
        ];
        return $data;
    }

    public function toArray(){
        $this->product->toArray();

    }
}