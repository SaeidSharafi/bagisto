<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\API\Http\Resources\Customer\CustomerGroup;

class JeduCustomerSMSRemaining extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
           'otp_expire'=>$this->otp_expire - time()
        ];
    }
    public function with($request)
    {
        return ['status' => 'success'];
    }
}
