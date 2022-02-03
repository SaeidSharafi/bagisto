<?php
/**
 * Created by PhpStorm.
 * User: Ali
 * Date: 12/23/2016
 * Time: 5:54 PM
 */

namespace Kuro\LaravelSms\Gateways;


interface GatewayInterface {

	/**
	 *
	 * @return mixed
	 */
	public function sendSms();

    /**
     *
     * @return mixed
     */
    public function sendPatternSms();

    /**
	 * @return int
	 */
	public function getCredit();
}