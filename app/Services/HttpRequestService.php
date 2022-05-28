<?php

namespace App\Services;

use App\Listeners\OrderListener;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class HttpRequestService
{
    protected $order;
    protected $operation;

    protected $key = '0bynTwlxQNducjLvsARtmhmbrbEg3UAY';

    const OP_UPDATE_REGISTERATION = 1;
    const OP_REGISTER_STUDENT = 2;
    const SYSTEM_USER_ID = 1013;
    const REGISTERED = 1;
    const CANCELED = 2;

    public function __construct($order,$operation)
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
        if ($customer->incomplete){
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
                'student_name'   => $customer->first_name,
                'student_family' => $customer->last_name,
                'tel'            => $customer->phone,
                'details'        => $customer->notes,
                'field_of_study' => $customer->education_field,
                'father_name'    => $customer->father_name,
                'birthday'       => $date_of_birth,
                'gender'         => ($customer->gender == 'Male') ? 1 : 2,
            ],

            'intro_method' => 3,
            'contact_way'  => 5,
            'details'      => $comments,
        ];


        foreach ($this->order->items as $item) {
            $registration = [
                'course_id' => $item->sku,
                'payment'   => [
                    'pay'  => $item->price - $item->discount_amount,
                    'bill' => "{$this->order->increment_id}-{$item->id}",
                    'date' => $item->created_at->format('Y-m-d'),
                ],
            ];
            $registrations['registeraions'][] = $registration;
        }
        $data = [
            'key'  => $this->key,
            'data' => $registrations
        ];

        $respons = Http::asForm()
            ->post('https://ims.jedu.ir/jedu/sql/insert/sync_test.php', $data);
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