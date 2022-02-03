<?php
/**
 * Created by PhpStorm.
 * User: Ali
 * Date: 12/23/2016
 * Time: 6:09 PM
 */

namespace Kuro\LaravelSms\Gateways;

use Kuro\LaravelSms\Sms;

abstract class GatewayAbstract extends Sms implements GatewayInterface
{

    public function __construct(Sms $sms)
    {
        parent::__construct();
        $this->username = $sms->username;
        $this->password = $sms->password;
        $this->message = $sms->getContent();
        $this->to = $sms->to;
        $this->pattern = $sms->pattern;
        $this->parameters = $sms->parameters;
        $this->is_flash = $sms->is_flash;
    }

}
