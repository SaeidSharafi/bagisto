<?php

namespace App\Services;

use App\Jobs\SendSMS;
use App\Models\Shop\Otp;
use App\Notifications\OtpNotification;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Notification;
use Kuro\LaravelSms\SmsData;

class OtpService
{
    public static function getOTP(User $user, $type = Otp::VERFIY, int $time = 300)
    {

        if (self::isExpired($user)) {
            $user->otp()->delete();
            $data['token'] = random_int(111111, 999999);
            $data['expire'] = strtotime("+{$time} seconds");
            $data['type'] = $type;
            $otp = $user->otp()->create($data);
            $user->notify(new OtpNotification());
            return $otp;
        }

        return $user->otp;
    }

    public static function clearOTP(User $user)
    {
        $user->otp()->delete();
    }

    public static function isExpired(User $user): bool
    {

        if (!$user->otp) {
            return true;
        }
        return $user->otp->isExpired();
    }
}