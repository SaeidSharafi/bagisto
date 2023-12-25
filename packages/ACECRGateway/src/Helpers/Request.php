<?php

namespace ACECRGateway\Helpers;

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

            $response = self::sendCurlRequest($url, $params);
            Log::info('makeRequest response', ['return' => $response]);

            if (preg_match("/\('([^']+)'/", $response, $matches)) {

                $ref = $matches[1];
                return [
                    'status' => self::SUCCESS,
                    'response' => $ref,
                ];
            }


            return [
                'status' => self::ERROR,
                'response' => $response,
            ];
        } catch (\Exception $e) {
            return [
                'status' => self::ERROR,
                'response' => 'SoapFault: '.$e->getMessage().' #'.$e->getCode(), $e->getCode(),
            ];
        }
    }

    public static function verify($url, $params = [])
    {
        try {
            $response = self::sendCurlRequest($url, $params);
            Log::info('verify response', ['response' => $response]);

            $result = json_decode($response, true, 512, JSON_THROW_ON_ERROR)['item1'];

            if ($result === true) {
                return [
                    'status' => self::SUCCESS,
                    'response' => true,
                ];
            }
            return [
                'status' => self::FAIL,
                'response' => $response,
            ];
        } catch (\Exception $e) {
            return [
                'status' => self::ERROR,
                'response' => 'SoapFault: '.$e->getMessage().' #'.$e->getCode(), $e->getCode(),
            ];
        }
    }

    public static function settle($url, $params = [])
    {
        try {
            $response = self::sendCurlRequest($url, $params);
            Log::info('settle response', ['response' => $response]);

            $result = json_decode($response, true, 512, JSON_THROW_ON_ERROR)['item1'];

            if ($result === true) {

                return [
                    'status' => self::SUCCESS,
                    'response' => true,
                ];
            }
            return [
                'status' => self::FAIL,
                'response' => $response,
            ];
        } catch (\SoapFault $e) {
            return [
                'status' => self::ERROR,
                'response' => 'SoapFault: '.$e->getMessage().' #'.$e->getCode(), $e->getCode(),
            ];
        }
    }

    private static function sendCurlRequest(string $endpoint, array $paramteres)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($paramteres, JSON_THROW_ON_ERROR),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}