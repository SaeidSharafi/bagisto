<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\API\Http\Resources\Customer\CustomerGroup;

class JeduCustomer extends JsonResource
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
            'id'            => $this->id,
            'email'         => $this->email,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'name'          => $this->name,
            'gender'        => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'phone'         => $this->phone,
            'status'        => $this->status,
            'group'         => $this->when($this->customer_group_id, new CustomerGroup($this->group)),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
