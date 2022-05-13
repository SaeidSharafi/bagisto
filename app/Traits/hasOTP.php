<?php

namespace App\Traits;

use App\Models\Shop\Otp;

trait hasOTP
{
    public function otp(){
        return $this->hasOne(Otp::class,'customer_id');
    }
}