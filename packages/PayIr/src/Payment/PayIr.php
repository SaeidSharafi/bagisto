<?php

namespace PayIr\Payment;

use PayIr\Exceptions\SendException;
use PayIr\Helpers\Request;
use Webkul\Payment\Payment\Payment;

class PayIr extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'payir';

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
        return route('paydotir.redirect');
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
        return core()->getConfigData('sales.paymentmethods.' . $this->getCode() . '.' . $field);
    }
}