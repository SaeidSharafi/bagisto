<?php

namespace App\Shop\Product\Types;

use App\Shop\Facades\ProductImage;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\Product\Type\Configurable;
use App\Services\CatalogRuleProductPriceService;

class JeduConfigurable extends Configurable
{

    /**
     * Get product minimal price.
     *
     * @return string
     */
    public function getPriceHtml()
    {

        if ($offer = $this->getOfferPrice()) {
            $amount = "";

            if ($offer['action_type'] === "by_percent") {
                $amount = '<span class="discount-amount">'
                    .core()->formatPercent($offer['discount_amount'])
                    .'</span>';
            }
            return '<div class="discount"><span class="regular-price">'
                .core()->currencyNoSymbole($this->evaluatePrice($this->getMaximamPrice()))
                .'</span>'
                .$amount
                .'</div>'
                .'<span class="final-price">'
                .core()->currency($this->evaluatePrice($offer['price']))
                .'</span>';
        }
        if (core()->getCurrentLocale()
            && core()->getCurrentLocale()->direction == 'rtl'
        ) {
            return '<span class="final-price">'
                .core()->currency($this->evaluatePrice($this->getMaximamPrice()))
                .'</span>';
        }
        return '<span class="final-price">'
            .core()->currency($this->evaluatePrice($this->getMinimalPrice()))
            .'</span>';

    }

    /**
     * Get product offer price.
     *
     * @return float
     */
    public function getOfferPrice()
    {
        $rulePrices = $customerGroupPrices = [];

        foreach ($this->product->variants as $variant) {
            $rulePrice
                = app(CatalogRuleProductPriceService::class)->getRulePrice($variant);

            if ($rulePrice) {
                $discount = [];
                $discount['price'] = $rulePrice->price;
                $discount['action_type'] = $rulePrice->action_type;
                $discount['discount_amount'] = $rulePrice->discount_amount;
                $rulePrices[] = $discount;
            }

            $customerGroupPrice['price']
                = $this->getCustomerGroupPrice($variant, 1);
            $customerGroupPrice['action_type'] = '';
            $customerGroupPrice['discount_amount'] = '';
            $rulePrices[] = $customerGroupPrice;

        }

        if ($rulePrices || $customerGroupPrices) {
            $offerPrice = array_reduce($rulePrices, function ($a, $b) {
                if ($a) {
                    return ($a['price'] < $b['price']) ? $a : $b;
                }
                return $b;
            });
            $minPrice = $this->getMinimalPrice();
            if ($offerPrice['price'] < $minPrice) {
                return $offerPrice;
            }

        }

        return null;
    }

    /**
     * Get product base image.
     *
     * @param  \Webkul\Customer\Contracts\Wishlist|\Webkul\Checkout\Contracts\CartItem  $item
     * @return array
     */
    public function getBaseImage($item)
    {
        if ($item instanceof \Webkul\Customer\Contracts\Wishlist) {
            if (isset($item->additional['selected_configurable_option'])) {
                $product = $this->productRepository->find($item->additional['selected_configurable_option']);
            } else {
                $product = $item->product;
            }
        } else {
            if ($item instanceof \Webkul\Checkout\Contracts\CartItem) {
                $product = $item->child->product;
            } else {
                if (count($item->child->product->images)) {
                    $product = $item->child->product;
                } else {
                    $product = $item->product;
                }
            }
        }

        return ProductImage::getProductBaseImage($product);
    }
}