<?php

namespace ACECRGateway\Payment;

use Webkul\Payment\Payment\Payment;

class ACECR extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'acecr';

    public $token;
    public $amount;
    public $redirect;
    public $factorNumber;
    public $mobile;
    public $description;
    public $paymentUrl;
    public $validCardNumber;

    public function getRedirectUrl()
    {
        return route('acecr.redirect');
    }

    /**
     * Retrieve information from payment configuration
     *
     * @param  string  $field
     * @param  int|string|null  $channelId
     * @return mixed
     */
    public function getConfigData($field)
    {
        return core()->getConfigData('sales.paymentmethods.'.$this->getCode().'.'.$field);
    }
}