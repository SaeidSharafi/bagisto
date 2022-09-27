<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kuro\LaravelSms\Sms;
use Kuro\LaravelSms\SmsChannel;
use Kuro\LaravelSms\SmsData;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $order;

    protected $pattern;

    protected $enabled_status = ['processing'];

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
            \Log::info("No need to send sms: ".$this->order->status);
            return null;
        }

        $from = core()->getConfigData('sms.configure.sms_settings.from')
            ?: config('sms.gateway.rangine.from');
        $username = core()->getConfigData('sms.configure.sms_settings.username')
            ?: config('sms.gateway.rangine.username');
        $password = core()->getConfigData('sms.configure.sms_settings.password')
            ?: config('sms.gateway.rangine.password');

        $pattern = $this->pattern
            ?: core()->getConfigData('sms.general.notifications.new-order.pattern');

        \Log::info("Sending Sms for new order");
        \Log::info("order status is -> {$this->order->status}");
        $parameters = ['invoice_no' => $this->order->increment_id];


        return (new Sms)
            ->from($from)
            ->username($username)
            ->password($password)
            ->to([$this->order->customer_phone])
            ->pattern($pattern ?: "s3u9issn2i")
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
