<?php

namespace App\Services;

use App\Jobs\SendSMS;
use App\Notifications\OtpNotification;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Notification;
use Kuro\LaravelSms\SmsData;

class OTP
{
    public static function getOTP(User $user, int $time = 300)
    {
        if (self::isExpired($user)) {
            $user->otp = random_int(111111, 999999);
            $user->otp_expire = strtotime("+{$time} seconds");
            $user->save();
            $user->notify(new OtpNotification());
            return $user;
        }
        return  $user;
    }

    public static function clearOTP(User $user)
    {
        $user->otp = null;
        $user->otp_expire = null;
        $user->save();
    }

    public static function isExpired(User $user): bool
    {
        return (($user->otp_expire - time()) <= 0);
    }

    public static function remaining(User $user): int
    {
        return $user->otp_expire - time();
    }
}