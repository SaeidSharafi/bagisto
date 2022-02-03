<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kuro\LaravelSms\Sms;
use Kuro\LaravelSms\SmsChannel;
use Kuro\LaravelSms\SmsData;

class OrderCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;


    private $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;

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


        \Log::info("Sending Sms With Notifications");

        return (new Sms)
            ->from($from)
            ->username($username)
            ->password($password)
            ->to([$this->comment->order->customer_phone])
            ->line($this->comment->comment ?: "آزمایشی")
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
