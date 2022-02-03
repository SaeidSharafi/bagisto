<?php

namespace Kuro\LaravelSms\Gateways;

interface GatewaySendPattern
{

    /**
     *
     * @return mixed
     */
    public function sendPatternSms();
}