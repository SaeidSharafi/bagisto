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
        $this->from = $this->from ?: config('sms.gateway.rangine.from');

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
            if (config(('app.debug'))) {
                \Log::info("sending sms with from :".$this->from);
                \Log::info("sending sms with to :".implode(",", $this->to));
                \Log::info("sending sms with username :".$this->username);
                \Log::info("sending sms with password :".$this->password);
                \Log::info("sending sms with pattern :".$this->pattern);
                \Log::info("sending sms with parameters :".implode(",", $this->parameters));
            }
            return (new \SoapClient($this->webService))->sendPatternSms(
                $this->from,
                $this->to,
                $this->username,
                $this->password,
                $this->pattern,
                $this->parameters
            );
        } catch (SoapFault $ex) {
            \Log::error("error sending sms with rangine:", $ex);
            throw CouldNotSendNotification::serviceRespondedWithAnError(
                $ex->getMessage(),
                $ex->getCode()
            );
        }
    }
}