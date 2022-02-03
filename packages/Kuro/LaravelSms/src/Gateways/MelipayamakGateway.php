<?php

namespace Kuro\LaravelSms\Gateways;

use Kuro\LaravelSms\Sms;

/**
 * Created by PhpStorm.
 * User: Ali
 * Date: 12/23/2016
 * Time: 12:51 PM
 */
class MelipayamakGateway extends GatewayAbstract {

	/**
	 * AdpdigitalGateway constructor.
	 */
	public function __construct(Sms $sms) {
        parent::__construct($sms);
        $this->username    =$this->username ?: config('sms.gateway.melipayamak.username');
        $this->password    =$this->password ?: config('sms.gateway.melipayamak.password');
        $this->from        =$this->from     ?: config('sms.gateway.melipayamak.from');
		$this->webService  = config('sms.gateway.melipayamak.webService');


	}


	/**
	 * @return mixed
	 * @internal param $to | array
	 */
	public function sendSms( ) {
		try {
			// Check credit for the gateway
			if ( ! $this->GetCredit() ) {
				return false;
			}
			$client = new \SoapClient( $this->webService );
			$result = $client->SendSms(
				[
					'username' => $this->username,
					'password' => $this->password,
					'from'     => $this->from,
					'to'       => $this->to,
					'text'     => $this->message,
					'isflash'  => $this->is_flash,
					'udh'      => '',
					'recId'    => [ 0 ],
					'status'   => 0x0,
				]
			);

			return $result->SendSmsResult;
		} catch( SoapFault $ex ) {
			return $ex->faultstring;
		}
	}

    /**
     * @return mixed
     * @internal param $to | array
     */
    public function sendPatternSms( ) {
        try {
            // Check credit for the gateway
            if ( ! $this->GetCredit() ) {
                return false;
            }
            $client = new \SoapClient( $this->webService );
            $result = $client->SendSms(
                [
                    'username' => $this->username,
                    'password' => $this->password,
                    'from'     => $this->from,
                    'to'       => $this->to,
                    'text'     => $this->message,
                    'isflash'  => $this->is_flash,
                    'udh'      => '',
                    'recId'    => [ 0 ],
                    'status'   => 0x0,
                ]
            );

            return $result->SendSmsResult;
        } catch( SoapFault $ex ) {
            return $ex->faultstring;
        }
    }
	/**
	 * @return mixed
	 */
	public function getCredit() {
		try {
			$client = new \SoapClient( $this->webService );

			return $client->GetCredit( [
				                           "username" => $this->username,
				                           "password" => $this->password,
			                           ] )->GetCreditResult;
		} catch( SoapFault $ex ) {
			return $ex->faultstring;
		}
	}

}