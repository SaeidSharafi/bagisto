<?php

namespace App\Services;

use App\Listeners\OrderListener;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class HttpRequestService
{
    protected $order;
    protected $operation;


    const OP_UPDATE_REGISTERATION = 1;
    const OP_REGISTER_STUDENT = 2;
    const SYSTEM_USER_ID = 1013;
    const REGISTERED = 1;
    const CANCELED = 2;

    public function __construct($order, $operation)
    {
        $this->order = $order;
        $this->operation = $operation;
    }

    public function build()
    {
        switch ($this->operation) {
            case self::OP_UPDATE_REGISTERATION:
                return $this->UpdateRegisteration();
        }
        return $this;
    }

    public function UpdateRegisteration()
    {
        $registrations = [];
        if ($this->order->status !== "completed"
            && $this->order->status !== "closed"
            && $this->order->status !== "canceled"
        ) {
            return "unnecssary update";
        }
        $customer = $this->order->customer;
        if ($customer->incomplete) {
            return "customer profile incomplete";
        }

        $date_of_birth = $customer->date_of_birth;
        if ($date_of_birth instanceof Carbon) {
            $date_of_birth = $date_of_birth->format('Y-m-d');
        }

        $comments = $this->order->comments->pluck('comment')->join(" || ");
        $registrations = [
            'user_id'            => self::SYSTEM_USER_ID,
            'created'            => $this->order->created_at->format('Y-m-d h:i:s'),
            'register_date'      => $this->order->created_at->format('Y-m-d h:i:s'),
            'registration_state' => $this->order->status === "completed" ? self::REGISTERED : self::CANCELED,
            'national_code'      => $customer->national_code,
            'student'            => [
                'national_code'  => $customer->national_code,
                'created'        => $customer->created_at->format('Y-m-d h:i:s'),
                'first_name'   => $customer->first_name,
                'last_name' => $customer->last_name,
                'phone'            => $customer->phone,
                'note'        => $customer->notes,
                'field_of_study' => $customer->education_field,
                'father_name'    => $customer->father_name,
                'date_of_birth'       => $date_of_birth,
                'gender'         => ($customer->gender == 'Male') ? 1 : 2,
            ],

            'intro_method'  => 3,
            'contact_way'   => 5,
            'discount_code' => $this->order->coupon_code ?: '',
            'details'       => $comments,
        ];

        foreach ($this->order->items as $item) {

            $product_number = $item->product_number;

            if ($item->type === 'configurable') {
                $product_number = $item->product_number ?: $item->child->product_number;
            }

            $registration = [
                'course_code' => $product_number,
                'payment'   => [
                    'discount_type'  => $item->base_discount_amount > 0 ? 'manual' : 'none',
                    'discount_amount' => (int)$item->discount_amount,
                    'amount'  => (int)$item->base_total_invoiced,
                    'bill' => $this->order->increment_id,
                    'date' => $item->created_at->format('Y-m-d'),
                ],
            ];
            $registrations['registeraions'][] = $registration;
        }
        $data = [
            'key'  => config('app.ims.api_key'),
            'data' => $registrations
        ];

        $url = config('app.ims.base_url') . '/api/v1/enrol';

        $respons = Http::asForm()
            ->post($url, $data);
        echo $respons->getBody();
        \Log::info($respons->getBody());
        if ($respons->successful()) {
            return $respons;
        }
        if ($respons->failed()) {
            return $respons;
        }
        if ($respons->clientError()) {
            return $respons;
        }
        if ($respons->serverError()) {
            return "serverError";
        }
        return $respons;
    }
}
