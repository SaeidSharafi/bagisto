<?php

namespace App\Services;

use App\Listeners\OrderListener;
use App\Rules\Nationalcode;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Webkul\Sales\Models\Order;

class RouyeshAPIService
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
        if ($this->order->status !== "completed") {
            return false;
        }

        $username = config('app.rouyesh.username');
        $password = config('app.rouyesh.password');
        $apiUrl = config('app.rouyesh.base_url');
        if (!$username || !$password) {
            throw new \InvalidArgumentException(__('app.response.sync-ims-api-key'));
        }
        $registrations = [];

        $customer = $this->order->customer;
        if ($customer->incomplete) {
            throw new \InvalidArgumentException(__('app.response.sync-ims-customer-incomplete'));
        }

        //foreach ($this->order->items as $item) {
        //    if (!$item->product_number) {
        //        throw new \InvalidArgumentException(__('app.response.sync-ims-porduct-number'));
        //    }
        //}

        $respons = Http::asJson()
            ->post($apiUrl.'/Users/login', [
                'username' => $username,
                'password' => $password,
            ]);

        $data = $respons->json();
        $cityCode = data_get($data, 'data.UserCityCenters.0.CityID');
        $token = data_get($data, 'token');

        $date_of_birth = $customer->date_of_birth;
        if ($date_of_birth instanceof Carbon) {
            $date_of_birth = $date_of_birth->format('Y-m-d');
        }

        $isForeigner = Validator::make(
            ['string' => '123'],
            ['string' => new Nationalcode()]
        )->fails();

        $cutomerResposne = Http::asJson()
            ->withToken($token)
            ->post($apiUrl.'/Students/DT', [
                'columns' =>
                    [
                        [
                            'data'     => $isForeigner ? 'User_PassportNumber' : 'User_NationalCode',
                            'latinopt' => '=',
                            'search'   => ['value' => '123'],
                            //'search'   => ['value' => $customer->national_code],
                        ]
                    ]
            ]);
        $rouyeshStudent = $cutomerResposne->json('data');
        $studentId = data_get($rouyeshStudent, '0.StudentID');

        if (!$studentId) {
            $cutomerResposne = Http::asJson()
                ->withToken($token)
                ->post($apiUrl.'/Users', [
                    "firstName"      => $customer->first_name,
                    "lastName"       => $customer->last_name,
                    "password"       => $customer->national_code,
                    "retypePassword" => $customer->national_code,
                    "username"       => '123Username',
                    "cityID"         => $cityCode,
                    "sex"            => $customer->gender === 'Male',
                    "isActive"       => true,
                    "isForeigner"    => $isForeigner,
                    "nationalCode"   => $isForeigner ? null : $customer->national_code,
                    "passportNumber" => $isForeigner ? '123' : null,
                    "mobile"         => $customer->phone,
                    "otherInfo"      => [
                        "fatherName" => $customer->father_name,
                        "birthDate"  => $customer->date_of_birth->jdate('Y/m/d', 'en'),
                        "email"      => $customer->email,
                    ]
                ]);
            $resposne = $cutomerResposne->json();
            if ($errors = data_get($resposne, 'errors')) {
                dump($errors);
                Log::error($errors);
                throw new \Exception('Customer Creation failed');
            }
            $userId = data_get($resposne, 'data.UserID');
            if (!$userId) {
                throw new \Exception('َسثق Creation failed');
            }

            $resposne = Http::withToken($token)
                ->asJson()
                ->post($apiUrl.'/Students/'.$cityCode, [
                    "isActive" => true,
                    'userID'   => $userId,
                ]);

            $studentId = $resposne->json('StudentID');
            if (!$studentId) {
                throw new \Exception('Customer Creation failed');
            }

        }

        $comments = $this->order->comments->pluck('comment')->prepend('ثبت‌نام آنلاین')->join(" || ");
        foreach ($this->order->items as $item) {
            $product = $item->product->product_flats->first();
            if ($item->type === 'configurable') {
                $child = $item->child->product->product_flats->first();
                if ($child->rouyesh_code) {
                    $product = $child;
                }
            }

            $registration = [
                'studentID'       => $studentId,
                'classroomID'     => $product->rouyesh_code,
                'RegisterTypeID' => 1,
                'discountInfo'    => [
                    'discountTypeID' => 4,
                    'discountPrice'   => 2000
                ],
                'cashPaymentInfo' => [
                    "accountNumberID" => 75,
                    "receiptNumber"   => "Order-".$this->order->increment_id,
                    "receiptDate"     => $this->order->created_at->jdate('Y/m/d', 'en'),
                    "paymentedAmount" => (int) $this->order->grand_total
                ],
                'description'     => $comments,
            ];

            $response = Http::withToken($token)
                ->asJson()
                ->post($apiUrl.'/Students/Register/', $registration);
            dd($response);
            if ($respons->ok()) {
                $enrolment = $respons->json('enrolment');
                if (!$this->order->rouyesh_synced_at) {
                    Order::where('id', $this->order->id)->update([
                        'rouyesh_synced_at'    => now(),
                        'rouyesh_enrolment_id' => $enrolment['StudentRegisterID']
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

        }

        return false;
    }
}
