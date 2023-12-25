<?php

namespace MellatGateway\Helpers;

use Illuminate\Support\Facades\Log;

class Request
{

    public const SUCCESS = 1;
    public const ERROR = -1;
    public const EXIST = 2;
    public const FAIL = 3;
    public const CANCEL = 4;

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

                $return = explode(',', $response->return);

                if ($return[0] == 0) {
                    return [
                        'status' => self::SUCCESS,
                        'response' => $return[1],
                    ];
                }

                return [
                    'status' => self::FAIL,
                    'response' => $return[0],
                ];

            }
            return [
                'status' => self::ERROR,
                'response' => 'larapay::larapay.invalid_response',
            ];
        } catch (\SoapFault $e) {
            return [
                'status' => self::ERROR,
                'response' => 'SoapFault: '.$e->getMessage().' #'.$e->getCode(), $e->getCode(),
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

                if ($response->return == 0 || $response->return == 43) {
                    return [
                        'status' => self::SUCCESS,
                        'response' => true,
                    ];
                }
                return [
                    'status' => self::FAIL,
                    'response' => $response->return,
                ];

            }
            return [
                'status' => self::ERROR,
                'response' => 'larapay::larapay.invalid_response',
            ];
        } catch (\SoapFault $e) {
            return [
                'status' => self::ERROR,
                'response' => 'SoapFault: '.$e->getMessage().' #'.$e->getCode(), $e->getCode(),
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
                        'status' => self::SUCCESS,
                        'response' => true,
                    ];
                }
                return [
                    'status' => self::FAIL,
                    'response' => $response->return,
                ];

            }
            return [
                'status' => self::ERROR,
                'response' => 'larapay::larapay.invalid_response',
            ];
        } catch (\SoapFault $e) {
            return [
                'status' => self::ERROR,
                'response' => 'SoapFault: '.$e->getMessage().' #'.$e->getCode(), $e->getCode(),
            ];
        }
    }
}