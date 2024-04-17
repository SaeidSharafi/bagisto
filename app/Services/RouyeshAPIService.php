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
use Webkul\Notification\Repositories\NotificationRepository;
use Webkul\Sales\Models\Order;

class RouyeshAPIService
{
    protected $order;
    protected $operation;
    protected $notificationRepository;

    const OP_UPDATE_REGISTERATION = 1;
    const OP_REGISTER_STUDENT = 2;
    const SYSTEM_USER_ID = 1013;
    const REGISTERED = 1;
    const CANCELED = 2;

    public function __construct($order, $operation)
    {
        $this->order = $order;
        $this->operation = $operation;
        $this->notificationRepository = new NotificationRepository(app());
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
        if ($this->order->rouyesh_synced_at){
            return false;
        }
        $username = config('app.rouyesh.username');
        $password = config('app.rouyesh.password');
        $apiUrl = config('app.rouyesh.base_url');
        if (!$username || !$password) {
            throw new \InvalidArgumentException(__('app.response.sync-ims-api-key'));
        }

        $customer = $this->order->customer;
        if ($customer->incomplete) {
            throw new \InvalidArgumentException(__('app.response.sync-ims-customer-incomplete'));
        }


        $respons = Http::asJson()
            ->post($apiUrl.'/Users/login', [
                'username' => $username,
                'password' => $password,
            ]);

        $data = $respons->json();
        $cityCode = data_get($data, 'data.UserCityCenters.0.CityID');
        $token = data_get($data, 'token');

        $isForeigner = Validator::make(
            ['string' => $customer->national_code],
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
                            //'search'   => ['value' => '123'],
                            'search'   => ['value' => $customer->national_code],
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
                    "username"       => $customer->national_code,
                    "cityID"         => $cityCode,
                    "sex"            => $customer->gender === 'Male',
                    "isActive"       => true,
                    "isForeigner"    => $isForeigner,
                    "nationalCode"   => $isForeigner ? null : $customer->national_code,
                    "passportNumber" => $isForeigner ? $customer->national_code : null,
                    "mobile"         => $customer->phone,
                    "otherInfo"      => [
                        "fatherName" => $customer->father_name,
                        "birthDate"  => $customer->date_of_birth->jdate('Y/m/d', 'en'),
                        "email"      => $customer->email,
                    ]
                ]);
            $response = $cutomerResposne->json();
            if ($response->status() === 400) {
                $responseJson = $response->json();
                Log::error('encountered error while trying ot create user in rouyesh', $responseJson);
                $this->notifyError($responseJson, $customer);
                throw new \Exception('User Creation failed');
            }

            $userId = data_get($response, 'data.UserID');
            if (!$userId) {
                throw new \Exception('User Creation failed');
            }

            $response = Http::withToken($token)
                ->asJson()
                ->post($apiUrl.'/Students/'.$cityCode, [
                    "isActive" => true,
                    'userID'   => $userId,
                ]);
            if ($response->status() === 400) {
                $responseJson = $response->json();
                Log::error('encountered error while trying ot create student in rouyesh', $responseJson);
                $this->notifyError($responseJson, $customer);
                throw new \Exception('Customer Creation failed');

            }
            $studentId = $response->json('StudentID');
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
                'studentID'              => $studentId,
                'classroomID'            => $product->rouyesh_code,
                'RegisterTypeID'         => 1,
                'discountInfo'           => [
                    'DiscountCode'    => '',
                    'DiscountGroupID' => -1,
                    'DiscountPercent' => 0,
                    'discountTypeID'  => (int) $item->discount_amount > 0 ? 4 : 5,
                    'discountPrice'   => (int) $item->discount_amount
                ],
                'cashPaymentInfo'        => [
                    "accountNumberID" => 75,
                    "receiptNumber"   => "shop-".$this->order->increment_id,
                    "receiptDate"     => $item->created_at->jdate('Y/m/d', 'en'),
                    "paymentedAmount" => (int) $this->order->grand_total
                ],
                'description'            => $comments,
                'CheqPaymentInfo'        => null,
                'HekmatPaymentInfo'      => null,
                'OrganizationContractID' => null,
                'InstallmentCountForPay' => 1,
            ];

            $response = Http::withToken($token)
                ->asJson()
                ->post($apiUrl.'/Students/Register/', $registration);

            if ($response->ok()) {
                Order::where('id', $this->order->id)->update([
                    'rouyesh_synced_at' => now(),
                ]);
                return true;
            }

            if ($response->status() === 400) {
                $responseJson = $response->json();
                Log::error('encountered error while trying ot enrol user in rouyesh', $responseJson);
                $this->notifyError($responseJson, $customer);
                return false;
            }
            if ($response->status() === 404) {
                throw new \InvalidArgumentException(__('app.response.sync-ims-course-notfound'));
            }
            if ($response->unauthorized()) {
                throw new AuthenticationException($respons->json('message'));
            }
            if ($response->failed()) {
                Log::error('Registring enrolment in IMS failed: \n'.$respons->body());
            }

        }

        return false;
    }

    protected function notifyError(array $response, $customer)
    {
        if ($errors = data_get($response, 'errors')) {
            $msg = __('admin::app.admin.system.customer').' '.$customer->first_name.' '.$customer->last_name;
            $msg .= '، سفارش '.$this->order->increment_id.' خطا: ';
            $msg .= $errors[0]['propertyErrors'][0];
            $this->notificationRepository->create([
                'type'     => 'rouyesh',
                'message'  => $msg,
                'order_id' => null
            ]);
        }
    }
}
