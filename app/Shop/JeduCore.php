<?php

namespace App\Shop;

class JeduCore extends \Webkul\Core\Core
{

    /**
     * Format and convert price with currency symbol.
     *
     * @param float $price
     *
     * @return string
     */
    public function currency($amount = 0)
    {
        if (is_null($amount)) {
            $amount = 0;
        }
        return $this->formatPrice($this->convertPrice($amount), $this->getCurrentCurrency()->code);
    }

    /**
     * Format and convert price with currency symbol.
     *
     * @param float $price
     *
     * @return string
     */
    public function currencyNoSymbole($amount = 0)
    {
        if (is_null($amount)) {
            $amount = 0;
        }
        return $this->formatPrice($this->convertPrice($amount));
    }

    /**
     * Format and convert price with currency symbol.
     *
     * @param float $price
     *
     * @return string
     */
    public function formatPrice($price, $currencyCode = null)
    {
        if (is_null($price))
            $price = 0;

        $formatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);
        if (core()->getCurrentLocale()->direction =="rtl"){
            $formatter->setPattern("#,##0\xC2\xA0¤");
        }
        if (!$currencyCode){
            $formatter->setPattern("#,##0");
        }
        return $formatter->formatCurrency($price, $currencyCode);
    }

    /**
     * Format price with base currency symbol. This method also give ability to encode
     * the base currency symbol and its optional.
     *
     * @param  float $price
     * @param  bool  $isEncoded
     *
     * @return string
     */
    public function formatBasePrice($price, $isEncoded = false)
    {
        if (is_null($price)) {
            $price = 0;
        }

        $formater = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);

        if ($symbol = $this->getBaseCurrency()->symbol) {

            if ($this->currencySymbol($this->getBaseCurrencyCode()) == $symbol) {
                if (core()->getCurrentLocale()->direction =="rtl"){
                    $formater->setPattern("#,##0\xC2\xA0¤");
                }

                $content = $formater->formatCurrency($price, $this->getBaseCurrencyCode());


            } else {
                $formater->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $symbol);

                $content = $formater->format($this->convertPrice($price));
            }
        } else {
            $content = $formater->formatCurrency($price, $this->getBaseCurrencyCode());
        }

        return ! $isEncoded ? $content : htmlentities($content);
    }

    /**
     * Format and convert number to locale.
     *
     * @param float $number
     *
     * @return string
     */
    public function formatPercent($number)
    {
        if (is_null($number))
            $number = 0;

        $formatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::ROUND_FLOOR);

        return $formatter->format($number)."%";
    }
}