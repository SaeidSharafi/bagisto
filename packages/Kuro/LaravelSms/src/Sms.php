<?php
/**
 * Created by PhpStorm.
 * User: Ali
 * Date: 12/23/2016
 * Time: 6:09 PM
 */

namespace Kuro\LaravelSms;

use Kuro\LaravelSms\Gateways\GatewayAbstract;
use Kuro\LaravelSms\Gateways\MelipayamakGateway;
use Kuro\LaravelSms\Gateways\RangineGateway;

class Sms
{

    protected $webService;

    protected $key;
    /**
     * username of SMS provider
     *
     * @var string
     */
    public $username;

    /**
     * password of SMS provider
     *
     * @var string
     */
    public $password;

    /**
     * Array of numbers which sms will be sent to
     *
     * @var array
     */
    public $to;

    /**
     * @var array
     */
    protected $payload
        = [
            'lines' => [],
        ];

    /**
     * pattern code for sending instant SMS
     *
     * @var string
     */
    public $pattern;

    /**
     * paramaters for replacing values in pattern
     *
     * @var string
     */
    public $parameters;

    /**
     * send SMS as flash
     *
     * @var boolean
     */
    public $is_flash;

    /**
     * send SMS as flash
     *
     * @var boolean
     */
    public $dry_run;


    /**
     * The numebr which SMS will be sent
     *
     * @var string
     */
    public $from;

    /**
     * Gateway class
     *
     * @var GatewayAbstract
     */
    protected $gateway;

    /**
     * Sms constructor.
     *
     * @param  string  $gateway
     */
    public function __construct()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
    }

    /**
     * @param $defaultGateway
     *
     */
    public function initGateway($defaultGateway = null): Sms
    {
        if (!$defaultGateway) {
            $defaultGateway = config('sms.default');
        }
        \Log::info("Initializing Gateways");
        switch ($defaultGateway) {
            case 'melipayamak':
                $this->gateway = new MelipayamakGateway($this);
                break;
            case 'rangine':
            default:

                $this->gateway = new RangineGateway($this);
                break;
        }

        return $this;
    }

    /**
     * set username
     *
     */
    public function username($username): Sms
    {
        $this->username = $username;
        return $this;
    }

    /**
     * set password
     *
     */
    public function password($password): Sms
    {
        $this->password = $password;
        return $this;
    }

    /**
     * set sms sender number
     *
     */
    public function from($number): Sms
    {
        $this->from = $number;

        return $this;
    }

    /**
     * set sms receiver number
     *
     */
    public function to(array $numbers): Sms
    {
        $this->to = $numbers;
        \Log::info("Sending Sms to".implode(",", $numbers));
        return $this;
    }

    /**
     * @param $line
     *
     * @return $this
     */
    public function line($line): Sms
    {
        $this->payload['lines'][] = $line;
        return $this;
    }

    /**
     * set pattern code
     *
     */
    public function parameters($parameters): Sms
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * set pattern code
     *
     */
    public function pattern($pattern): Sms
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * set is_flash
     *
     */
    public function isFlash($is_flash): Sms
    {
        $this->is_flash = $is_flash;
        return $this;
    }

    /**
     * @return string
     */
    public function dryRun(): Sms
    {
        $this->dry_run = true;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return implode(PHP_EOL, $this->payload['lines']);
    }

    /**
     * @inheritDoc
     */
    public function send()
    {
        if ($this->dry_run){
            \Log::info("Sending sms to [" .implode(",",$this->to) ."]");
            return "Simualted Sms Sending";
        }
        if ($this->pattern) {
            return $this->gateway->sendPatternSms();
        }
        return $this->gateway->sendSms();
    }

}
