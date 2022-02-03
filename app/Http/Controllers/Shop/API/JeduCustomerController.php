<?php

namespace App\Http\Controllers\Shop\API;

use App\Jobs\SendSMS;
use App\Services\OTP;
use App\Services\PrepareOtpSms;
use App\Services\SmsBuilder;
use Illuminate\Http\JsonResponse;
use Kuro\LaravelSms\Sms;

class JeduCustomerController
    extends \Webkul\API\Http\Controllers\Shop\CustomerController
{
    public function getCustmerRemainingTime($phone)
    {
        $customer = $this->customerRepository
            ->findOneByField('phone', $phone);
        return new $this->_config['resource']($customer);

    }

    public function resendSms()
    {
        $customer
            = $this->customerRepository->findOneByField('phone',
            request()->phone);
        if (!$customer) {
            return $this->sendError(
                "Customer not found.",
                [trans('responses.customer_not_found')],
                404
            );
        }
        if (core()->getConfigData('customer.settings.sms.verification')) {
            if (core()->getConfigData('sms.general.notifications.verification.status')) {
                $customer = OTP::getOTP($customer);
                return $this->sendResponse([
                    'remainTime' => OTP::remaining($customer)
                ], "");
            }
        }
        return $this->sendError(
            "SMS Disabled",
            [trans('responses.session_expired')],
            405
        );

    }

    public function checkOtpStatus(){
        if (session('otp_sent')){
            return $this->sendResponse("", "");
        }
        if (session('otp_failed')){
            return $this->sendError("", "");
        }
        return $this->sendResponse("sadsad", "");
    }
    /**
     * success response method.
     *
     * @param $result
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'status'  => 'success',
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param  array  $errorMessages
     * @param  int  $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'status'  => 'error',
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}