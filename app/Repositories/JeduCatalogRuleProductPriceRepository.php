<?php

namespace App\Repositories;

use Illuminate\Support\Carbon;
use Webkul\Core\Eloquent\Repository;

class JeduCatalogRuleProductPriceRepository
    extends Repository
{
    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\CatalogRule\Contracts\CatalogRuleProductPrice';
    }

    /**
     * Check if catalog rule prices already loaded. If already loaded then load from it.
     *
     * @return object
     */
    public function checkInLoadedCatalogRulePrice($product, $customerGroupId)
    {
        static $catalogRulePrices = [];

        if (array_key_exists($product->id, $catalogRulePrices)) {
            return $catalogRulePrices[$product->id];
        }
        return $this->scopeQuery( function ($query) use ($product,$customerGroupId){
            return $query->distinct()
                ->addSelect('catalog_rule_product_prices.*')
                ->addSelect('catalog_rules.action_type','catalog_rules.discount_amount')
                ->leftJoin('catalog_rules','catalog_rules.id','=','catalog_rule_product_prices.catalog_rule_id')
                ->where('product_id',$product->id)
                ->where('channel_id',core()->getCurrentChannel()->id)
                ->where('customer_group_id',$customerGroupId)
                ->where('rule_date',Carbon::now()->format('Y-m-d'));

        })->first();

    }
}