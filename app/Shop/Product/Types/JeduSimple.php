<?php

namespace App\Shop\Product\Types;

use App\Services\CatalogRuleProductPriceService;
use phpDocumentor\Reflection\Types\Mixed_;
use Webkul\Product\Type\Simple;

class JeduSimple extends Simple
{
    public function getPriceHtml()
    {

        if ($this->haveSpecialPrice()) {
            $discount='تخفیف ویژه';
            if ($this->product->action_type === "by_percent") {
                $discount = core()->formatPercent($this->product->discount_amount);
            }
            return '<div class="discount"><span class="regular-price">'
                .core()->currencyNoSymbole($this->evaluatePrice($this->product->price))
                .'</span>'
                .'<span class=discount-amount>'.$discount.'</span>'
                .'</div>'
                .'<span class="final-price">'
                .core()->currency($this->evaluatePrice($this->product->special_price))
                .'</span>';

        }
        return '<span class="final-price">'
            .core()->currency($this->evaluatePrice($this->product->price))
            .'</span>';

    }

    public function getDiscountPercent(){
        return $this->product->discount_amount;
    }

    public function isPercentOffer(){
        return $this->product->action_type === "by_percent";
    }

    public function getProductPriceHtml()
    {

        if ($this->haveSpecialPrice()) {
            $amount = "";
            if ($this->product->action_type === "by_percent") {
                $amount = '<span class="discount-amount">'
                    .core()->formatPercent($this->product->discount_amount)
                    .'</span>';
            }
            return '<div class="discount"><span class="regular-price">'
                .core()->currencyNoSymbole($this->evaluatePrice($this->product->price))
                .'</span>'
                .$amount
                .'</div>'
                .'<span class="final-price">'
                .core()->currency($this->evaluatePrice($this->product->special_price))
                .'</span>';

        }
        return '<span  class="final-price">'
            .core()->currency($this->evaluatePrice($this->product->price))
            .'</span>';

    }

    /**
     * Have special price.
     *
     * @param  int  $qty
     *
     * @return mixed
     */
    public function haveSpecialPrice($qty = null)
    {
        $customerGroupPrice = $this->getCustomerGroupPrice($this->product,
            $qty);

        $rulePrice
            = app(CatalogRuleProductPriceService::class)->getRulePrice($this->product);

        $specialPrice = $this->product->special_price;

        if ((is_null($specialPrice) || !(float) $specialPrice)
            && !$rulePrice
            && $customerGroupPrice == $this->product->price
        ) {
            return false;
        }

        $haveSpecialPrice = false;

        if (!(float) $specialPrice) {
            if ($rulePrice && $rulePrice->price < $this->product->price) {
                $this->product->special_price = $rulePrice->price;
                $this->product->action_type = $rulePrice->action_type;
                $this->product->discount_amount = $rulePrice->discount_amount;
                $haveSpecialPrice = true;
            }
        } else {
            if ($rulePrice
                && $rulePrice->price <= $this->product->special_price
            ) {
                $this->product->special_price = $rulePrice->price;

                $haveSpecialPrice = true;
            } else {
                if (core()->isChannelDateInInterval(
                    $this->product->special_price_from,
                    $this->product->special_price_to
                )
                ) {
                    $haveSpecialPrice = true;
                } elseif ($rulePrice) {
                    $this->product->special_price = $rulePrice->price;

                    $haveSpecialPrice = true;
                }
            }
        }

        if ($haveSpecialPrice) {
            $this->product->special_price = min($this->product->special_price,
                $customerGroupPrice);
        } else {
            if ($customerGroupPrice !== $this->product->price) {
                $haveSpecialPrice = true;
                $this->product->special_price = $customerGroupPrice;
            }
        }

        return $haveSpecialPrice;
    }
    /**
     * Returns additional information for items.
     *
     * @param  array  $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        $discount = $regular_price=0;
        if ($this->haveSpecialPrice()) {
            if ($this->isPercentOffer()) {
                $discount = $this>getDiscountPercent();
            }
            $regular_price =$this->evaluatePrice($this->getMaximamPrice());
        }
        $data['discount_data'] = [
            'discount'=>$discount,
            'regular_price'=>$regular_price,
        ];
        return $data;
    }
}