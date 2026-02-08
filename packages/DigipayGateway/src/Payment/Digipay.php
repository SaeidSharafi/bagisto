<?php

declare(strict_types=1);

namespace DigipayGateway\Payment;

use Webkul\Payment\Payment\Payment;

/**
 * Digipay payment method for Bagisto.
 */
class Digipay extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'digipay';

    /**
     * Get redirect URL for payment.
     *
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return route('digipay.redirect');
    }

    /**
     * Get payment method title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getConfigData('title') ?? __('digipay::messages.payment_title');
    }

    /**
     * Get payment method description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        $description = $this->getConfigData('description') ?? __('digipay::messages.payment_description');
        $description .= '<br>';
        $description .= '<strong>' . __('digipay::messages.minimum_order_amount') . ':</strong> ' . core()->currency($this->minimumOrderAmount(), [], false);
        return $description;
    }

    /**
     * Check if payment method is available.
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return (bool) $this->getConfigData('active');
    }

    /**
     * Get sort order.
     *
     * @return int
     */
    public function getSortOrder(): int
    {
        return (int) ($this->getConfigData('sort') ?? 0);
    }

    /**
     * Get config data.
     *
     * @param string $field
     * @return mixed
     */
    public function getConfigData($field)
    {
        return core()->getConfigData('sales.paymentmethods.digipay.' . $field);
    }

    public function minimumOrderAmount()
    {
        return $this->getConfigData('minimum_amount');
    }
}
