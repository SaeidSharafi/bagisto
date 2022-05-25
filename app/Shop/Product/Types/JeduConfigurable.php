<?php

namespace App\Shop\Product\Types;

use App\Shop\Facades\ProductImage;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\Product\Type\Configurable;
use App\Services\CatalogRuleProductPriceService;

class JeduConfigurable extends Configurable
{

    protected $fillableTypes = ['sku', 'name', 'url_key', 'short_description', 'description', 'price', 'status'];

    /**
     * Get product minimal price.
     *
     * @return string
     */
    public function getPriceHtml()
    {
        $discount = $regular_price = '';
        if ($offer = $this->getOfferPrice()) {
            $discount ='تخفیف ویژه';
            if ($offer['action_type'] === "by_percent") {
                $discount = core()->formatPercent($offer['discount_amount']);
            }
            $regular_price = core()->currencyNoSymbole($this->evaluatePrice($this->getMaximamPrice()));
            return '<div class="discount"><span class="regular-price">'
                .$regular_price
                .'</span>'
                .'<span class=discount-amount>'.$discount.'</span>'
                .'</div>'
                .'<span class="final-price">'
                .core()->currency($this->evaluatePrice($offer['price']))
                .'</span>';
        }
        if (core()->getCurrentLocale()
            && core()->getCurrentLocale()->direction == 'rtl'
        ) {
            return
                '<div class="discount d-none"><span class="regular-price"></span>'
                .'<span class=discount-amount></span>'
                .'</div>'
                .'<span class="final-price">'
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
     *
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

    /**
     * Create variant.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  array  $permutation
     * @param  array  $data
     *
     * @return \Webkul\Product\Contracts\Product
     */
    public function createVariant($product, $permutation, $data = [])
    {
        if (!count($data)) {
            $data = [
                'sku'         => $product->sku.'-variant-'.implode('-', $permutation),
                'name'        => '',
                'inventories' => [],
                'price'       => 0,
                'weight'      => 0,
            ];
        }

        $data = $this->fillRequiredFields($data);

        $typeOfVariants = 'simple';
        $productInstance = app(config('product_types.'.$product->type.'.class'));

        if (isset($productInstance->variantsType)
            && !in_array($productInstance->variantsType, ['bundle', 'configurable', 'grouped'])
        ) {
            $typeOfVariants = $productInstance->variantsType;
        }

        $variant = $this->productRepository->getModel()->create([
            'parent_id'           => $product->id,
            'type'                => $typeOfVariants,
            'attribute_family_id' => $product->attribute_family_id,
            'sku'                 => $data['sku'],
        ]);

        foreach ($this->fillableTypes as $attributeCode) {
            if (!isset($data[$attributeCode])) {
                continue;
            }

            $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);

            if ($attribute->value_per_channel) {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllChannels() as $channel) {
                        foreach (core()->getAllLocales() as $locale) {
                            $this->attributeValueRepository->create([
                                'product_id'   => $variant->id,
                                'attribute_id' => $attribute->id,
                                'channel'      => $channel->code,
                                'locale'       => $locale->code,
                                'value'        => $data[$attributeCode],
                            ]);
                        }
                    }
                } else {
                    foreach (core()->getAllChannels() as $channel) {
                        $this->attributeValueRepository->create([
                            'product_id'   => $variant->id,
                            'attribute_id' => $attribute->id,
                            'channel'      => $channel->code,
                            'value'        => $data[$attributeCode],
                        ]);
                    }
                }
            } else {
                if ($attribute->value_per_locale) {
                    foreach (core()->getAllLocales() as $locale) {
                        $this->attributeValueRepository->create([
                            'product_id'   => $variant->id,
                            'attribute_id' => $attribute->id,
                            'locale'       => $locale->code,
                            'value'        => $data[$attributeCode],
                        ]);
                    }
                } else {
                    $this->attributeValueRepository->create([
                        'product_id'   => $variant->id,
                        'attribute_id' => $attribute->id,
                        'value'        => $data[$attributeCode],
                    ]);
                }
            }
        }

        foreach ($permutation as $attributeId => $optionId) {
            $this->attributeValueRepository->create([
                'product_id'   => $variant->id,
                'attribute_id' => $attributeId,
                'value'        => $optionId,
            ]);
        }

        $this->productInventoryRepository->saveInventories($data, $variant);

        return $variant;
    }

    /**
     * Return validation rules.
     *
     * @return array
     */
    public function getTypeValidationRules()
    {
        return [
            'variants.*.name'  => 'required',
            'variants.*.sku'   => 'required',
            'variants.*.price' => 'required',
        ];
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param  array  $data
     *
     * @return array|string
     */
    public function prepareForCart($data)
    {
        if (!isset($data['selected_configurable_option']) || !$data['selected_configurable_option']) {
            if ($this->getDefaultVariantId()) {
                $data['selected_configurable_option'] = $this->getDefaultVariantId();
            } else {
                return trans('shop::app.checkout.cart.integrity.missing_options');
            }
        }

        $data = $this->getQtyRequest($data);
        $childProduct = $this->productRepository->find($data['selected_configurable_option']);


        if (!$childProduct->haveSufficientQuantity($data['quantity'])) {
            return trans('shop::app.checkout.cart.quantity.inventory_warning');
        }
        $price = $childProduct->getTypeInstance()->getFinalPrice();
        $discount = $regular_price=0;
        if ($childProduct->getTypeInstance()->haveSpecialPrice()) {
            if ($childProduct->getTypeInstance()->isPercentOffer()) {
                $discount = $childProduct->getTypeInstance()->getDiscountPercent();
            }
            $regular_price =$this->evaluatePrice($childProduct->getTypeInstance()->getMaximamPrice());
        }
        return [
            'parent' =>[
                'product_id' => $this->product->id,
                'sku'        => $this->product->sku,
                'name'       => $this->product->name,
                'type'       => $this->product->type,
                'quantity'   => $data['quantity'],
                'price'      => $convertedPrice = core()->convertPrice($price),
                'base_price' => $regular_price,
                'total'      => $convertedPrice * $data['quantity'],
                'base_total' => $price * $data['quantity'],
                'discount_percent' => $discount,
                'discount_amount' => $regular_price - $price,
                'additional' => $this->getAdditionalOptions($data),
            ],
            'variation'=>[
                'parent_id'  => $this->product->id,
                'product_id' => (int) $data['selected_configurable_option'],
                'sku'        => $childProduct->sku,
                'name'       => $childProduct->name,
                'type'       => $childProduct->type,
                'additional' => [
                    'product_id' => (int) $data['selected_configurable_option'],
                    'parent_id'  => $this->product->id,
                ],
            ],
        ];
    }
}