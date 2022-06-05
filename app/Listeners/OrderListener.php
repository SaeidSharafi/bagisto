<?php

namespace App\Listeners;

use App\Jobs\UpdateRegisteration;
use App\Services\HttpRequestService;
use App\Services\MoodleService;
use App\Traits\Sms;

class OrderListener
{
    use Sms;

    public function UpdateRegistration($order){
        //$request = new HttpRequestService($data);
        //$response = $request->build();
        //dd($order,$response);
        MoodleService::updateUserEnrolment($order);
        UpdateRegisteration::dispatch($order);
    }

}