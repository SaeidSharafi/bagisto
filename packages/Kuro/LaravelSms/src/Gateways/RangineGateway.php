<?php

namespace Kuro\LaravelSms\Gateways;

use Kuro\LaravelSms\Exceptions\CouldNotSendNotification;
use Kuro\LaravelSms\Sms;

class RangineGateway extends GatewayAbstract
{
    public function __construct(Sms $sms)
    {
        parent::__construct($sms);
        $this->webService = config('sms.gateway.rangine.webService');
        $this->username = $this->username ?: config('sms.gateway.rangine.username');
        $this->password = $this->password ?: config('sms.gateway.rangine.password');
        $this->from = $this->from     ?: config('sms.gateway.rangine.from');

    }

    /**
     * @inheritDoc
     */
    public function sendSms()
    {
        try {
            return (new \SoapClient($this->webService))->SendSms(
                $this->from,
                $this->to,
                $this->message,
                $this->username,
                $this->password,
                '',
                'send'
            );

        } catch (SoapFault $ex) {
            throw CouldNotSendNotification::serviceRespondedWithAnError(
                $ex->getMessage(),
                $ex->getCode()
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getCredit()
    {
        // TODO: Implement getCredit() method.
    }

    public function sendPatternSms()
    {
        try {
            return (new \SoapClient($this->webService))->sendPatternSms(
                $this->from,
                $this->to,
                $this->username,
                $this->password,
                $this->pattern,
                $this->parameters
            );
        } catch (SoapFault $ex) {
            throw CouldNotSendNotification::serviceRespondedWithAnError(
                $ex->getMessage(),
                $ex->getCode()
            );
        }
    }
}