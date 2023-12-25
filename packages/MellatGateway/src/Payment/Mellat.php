<?php

namespace MellatGateway\Payment;

use MellatGateway\Exceptions\SendException;
use MellatGateway\Helpers\Request;
use Webkul\Payment\Payment\Payment;

class Mellat extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'mellat';

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
        return route('mellat.redirect');
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