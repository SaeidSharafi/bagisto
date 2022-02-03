<?php

namespace App\Traits;

use App\Notifications\OrderCommentNotification;
use Illuminate\Support\Facades\Notification;
use Kuro\LaravelSms\SmsChannel;


trait Sms
{
    /**
     * Send order comment mail.
     *
     * @param  \Webkul\Sales\Contracts\OrderComment  $comment
     * @return void
     */
    public function sendOrderCommentSms($comment)
    {
        $customerLocale = $this->getLocale($comment);

        if (! $comment->customer_notified) {
            return;
        }

        try {
            /* email to customer */
            Notification::route('phone',$comment->order->customer_phone)
                ->notify(new OrderCommentNotification($comment));

            //$this->prepareMail($customerLocale, new OrderCommentNotification($comment));
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Get the locale of the customer if somehow item name changes then the english locale will pe provided.
     *
     * @param object \Webkul\Sales\Contracts\Order|\Webkul\Sales\Contracts\Invoice|\Webkul\Sales\Contracts\Refund|\Webkul\Sales\Contracts\Shipment|\Webkul\Sales\Contracts\OrderComment
     * @return string
     */
    private function getLocale($object)
    {
        if ($object instanceof \Webkul\Sales\Contracts\OrderComment) {
            $object = $object->order;
        }

        $objectFirstItem = $object->items->first();
        return isset($objectFirstItem->additional['locale']) ? $objectFirstItem->additional['locale'] : 'en';
    }
}