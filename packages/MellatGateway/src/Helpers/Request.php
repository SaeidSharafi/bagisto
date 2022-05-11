<?php

namespace MellatGateway\Helpers;

use Illuminate\Support\Facades\Log;

class Request
{
    /**
     * Make http request to Pay.ir
     *
     * @param $url
     * @param  array  $params
     *
     * @return array
     */
    public static function make($url, $params = [])
    {
        try {
            $soapClient = new \SoapClient($url, []);
            $response = $soapClient->bpPayRequest($params);

            if (isset($response->return)) {
                Log::info('bpPayRequest response', ['return' => $response->return]);

                $response = explode(',', $response->return);

                if ($response[0] == 0) {
                    return [
                        'status'   => 200,
                        'response' => $response[1],
                    ];
                }
                return [
                    'status'       => $response[0],
                    'errorMessage' => 'error',
                ];

            }
            return [
                'status'       => -1,
                'errorMessage' => 'larapay::larapay.invalid_response',
            ];
        } catch (\SoapFault $e) {
            return [
                'status'       => -1,
                'errorMessage' => 'SoapFault: '.$e->getMessage().' #'.$e->getCode(), $e->getCode(),
            ];
        }
    }

    public static function verify($url, $params = [])
    {
        try {
            $soapClient = new \SoapClient($url, []);
            $response = $soapClient->bpVerifyRequest($params);

            if (isset($response->return)) {
                Log::info('bpPayRequest response', ['response' => $response]);

                if ($response->return  == 0 || $response->return  == 43) {
                    return [
                        'status'   => 200,
                        'response' => true,
                    ];
                }
                return [
                    'status'       => $response->return ,
                    'errorMessage' => 'error',
                ];

            }
            return [
                'status'       => -1,
                'errorMessage' => 'larapay::larapay.invalid_response',
            ];
        } catch (\SoapFault $e) {
            return [
                'status'       => -1,
                'errorMessage' => 'SoapFault: '.$e->getMessage().' #'.$e->getCode(), $e->getCode(),
            ];
        }
    }

    public static function settle($url, $params = [])
    {
        try {
            $soapClient = new \SoapClient($url, []);
            $response = $soapClient->bpSettleRequest($params);

            if (isset($response->return)) {
                Log::info('bpPayRequest response', ['response' => $response]);

                if ($response->return == '0' || $response->return == '45') {
                    return [
                        'status'   => 200,
                        'response' => true,
                    ];
                }
                return [
                    'status'       => $response->return ,
                    'errorMessage' => 'error',
                ];

            }
            return [
                'status'       => -1,
                'errorMessage' => 'larapay::larapay.invalid_response',
            ];
        } catch (\SoapFault $e) {
            return [
                'status'       => -1,
                'errorMessage' => 'SoapFault: '.$e->getMessage().' #'.$e->getCode(), $e->getCode(),
            ];
        }
    }
}