<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kuro\LaravelSms\Sms;
use Kuro\LaravelSms\SmsChannel;
use Kuro\LaravelSms\SmsData;

class OrderRefundNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $order;

    protected $pattern;

    protected $enabled_status = ['closed'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order, $pattern = "")
    {
        $this->order = $order;
        $this->pattern = $pattern;

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
        // TODO implement other status messages
        if (!in_array($this->order->status, $this->enabled_status, false)) {
            \Log::info("No need to send sms");
            return null;
        }

        $from = core()->getConfigData('sms.configure.sms_settings.from')
            ?: config('sms.gateway.rangine.from');
        $username = core()->getConfigData('sms.configure.sms_settings.username')
            ?: config('sms.gateway.rangine.username');
        $password = core()->getConfigData('sms.configure.sms_settings.password')
            ?: config('sms.gateway.rangine.password');

        $pattern = $this->pattern
            ?: core()->getConfigData('sms.general.notifications.new-refund.pattern');

        \Log::info("Sending Sms for order refund");
        \Log::info("order status is -> {$this->order->status}");

        $parameters = [
            'name'   => $this->order->customer_first_name.' '.$this->order->customer_last_name,
            'course' => $this->order->items->first()->name,
        ];

        $to = $this->order->customer_phone ?: $this->order->customer->phone;
        return (new Sms)
            ->from($from)
            ->username($username)
            ->password($password)
            ->to([$to])
            ->pattern($pattern ?: "om5zt0jyce5e3gq")
            ->parameters($parameters)
            ->initGateway(core()->getConfigData('sms.configure.sms_settings.gateway'));
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
