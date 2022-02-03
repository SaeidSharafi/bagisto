<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kuro\LaravelSms\Sms;
use Kuro\LaravelSms\SmsChannel;
use Kuro\LaravelSms\SmsData;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $pattern;
    protected $parameters;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($parameters = "", $pattern = "")
    {
        $this->pattern = $pattern;
        $this->parameters = $parameters;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  SmsData  $notifiable
     *
     * @return Sms
     */
    public function toSms($notifiable)
    {
        $from = core()->getConfigData('sms.configure.sms_settings.from')
            ?: config('sms.gateway.rangine.from');
        $username = core()->getConfigData('sms.configure.sms_settings.username')
            ?: config('sms.gateway.rangine.username');
        $password = core()->getConfigData('sms.configure.sms_settings.password')
            ?: config('sms.gateway.rangine.password');
        $pattern = $this->pattern
            ?: core()->getConfigData('sms.general.notifications.verification.pattern');

        $parametres = $this->parameters ?: ['code' => $notifiable->otp];

        \Log::info("Sending Sms With Notifications");

        return (new Sms)
            ->from($from)
            ->username($username)
            ->password($password)
            ->to([$notifiable->phone])
            ->pattern($pattern ?: "mdoe1j1587")
            ->parameters($parametres)
            ->initGateway(core()->getConfigData('sms.configure.sms_settings.gateway'))
            ->dryRun();
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
