<?php

namespace App\Services;

use App\Listeners\OrderListener;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Models\Order;

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
        if (!$api_key = config('app.ims.api_key')) {
            throw new \InvalidArgumentException(__('app.response.sync-ims-api-key'));
        }
        $registrations = [];
        if ($this->order->status !== "completed") {
            return false;
        }

        $customer = $this->order->customer;
        if ($customer->incomplete) {
            throw new \InvalidArgumentException(__('app.response.sync-ims-customer-incomplete'));
        }

        foreach ($this->order->items as $item) {
            if (!$item->product_number) {
                throw new \InvalidArgumentException(__('app.response.sync-ims-porduct-number'));
            }
        }
        $date_of_birth = $customer->date_of_birth;
        if ($date_of_birth instanceof Carbon) {
            $date_of_birth = $date_of_birth->format('Y-m-d');
        }

        $comments = $this->order->comments->pluck('comment')->prepend('ثبت‌نام آنلاین')->join(" || ");
        $registrations = [
            'user_id'            => self::SYSTEM_USER_ID,
            'created'            => $this->order->created_at->format('Y-m-d h:i:s'),
            'register_date'      => $this->order->created_at->format('Y-m-d h:i:s'),
            'registration_state' => $this->order->status === "completed" ? self::REGISTERED : self::CANCELED,
            'national_code'      => $customer->national_code,
            'student'            => [
                'national_code'  => $customer->national_code,
                'created'        => $customer->created_at->format('Y-m-d h:i:s'),
                'first_name'     => $customer->first_name,
                'last_name'      => $customer->last_name,
                'phone'          => $customer->phone,
                'note'           => $customer->notes,
                'field_of_study' => $customer->education_field,
                'father_name'    => $customer->father_name,
                'date_of_birth'  => $date_of_birth,
                'gender'         => ($customer->gender == 'Male') ? 1 : 2,
            ],

            'intro_method'  => 3,
            'contact_way'   => 5,
            'discount_code' => $this->order->coupon_code ?: '',
        ];

        foreach ($this->order->items as $item) {

            $product_number = $item->product_number;

            if ($item->type === 'configurable') {
                $product_number = $item->product_number ?: $item->child->product_number;
            }

            $registration = [
                'course_code' => $product_number,
                'payment'     => [
                    'discount_type'   => $item->base_discount_amount > 0 ? 'manual' : 'none',
                    'discount_amount' => (int) $item->discount_amount,
                    'discount_code'   => $this->order->coupon_code,
                    'amount'          => (int) $this->order->grand_total,
                    'bill'            => $this->order->increment_id,
                    'date'            => $item->created_at->format('Y-m-d'),
                ],
                'note'        => $comments,
            ];
            $registrations['registeraions'][] = $registration;
        }
        $data = [
            'data' => $registrations
        ];

        $url = config('app.ims.base_url').'/api/v1/enrol';

        $respons = Http::withToken($api_key)
            ->asForm()
            ->post($url, $data);

        if ($respons->ok()) {
            $enrolment = $respons->json('enrolment');
            if (!$this->order->ims_synced_at) {
                Order::where('id', $this->order->id)->update([
                    'ims_synced_at'    => $enrolment['created_at'],
                    'ims_enrolment_id' => $enrolment['id']
                ]);
            }
            return true;
        }
        if ($respons->status() === 404) {
            throw new \InvalidArgumentException(__('app.response.sync-ims-course-notfound'));
        }
        if ($respons->unauthorized()) {
            throw new AuthenticationException($respons->json('message'));
        }
        if ($respons->failed()) {
            Log::error('Registring enrolment in IMS failed: \n'.$respons->body());
        }

        return false;
    }
}
