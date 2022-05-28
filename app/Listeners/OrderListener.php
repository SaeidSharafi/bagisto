<?php

namespace App\Listeners;

use App\Jobs\UpdateRegisteration;
use App\Services\HttpRequestService;
use App\Traits\Sms;

class OrderListener
{
    use Sms;

    public function changeImsRegistrationStatus($order){
        //$request = new HttpRequestService($data);
        //$response = $request->build();
        //dd($order,$response);
        UpdateRegisteration::dispatch($order);
    }
}