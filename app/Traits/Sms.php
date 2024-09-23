<?php

namespace App\Traits;

use App\Notifications\NewOrderNotification;
use App\Notifications\OrderCancelNotification;
use App\Notifications\OrderCommentNotification;
use App\Notifications\OrderCompletedNotification;
use App\Notifications\OrderPaymentCancelNotification;
use App\Notifications\OrderRefundNotification;
use Illuminate\Support\Facades\Notification;
use Webkul\Sales\Models\Order;

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
            /* send sms to customer */
            Notification::route('phone',$comment->order->customer_phone)
                ->notify(new OrderCommentNotification($comment));

            //$this->prepareMail($customerLocale, new OrderCommentNotification($comment));
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send new order Mail to the customer and admin.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function sendNewOrderSms($order)
    {
        //$customerLocale = $this->getLocale($order);

        try {
            /**
             * Sms to customer.
             */
            $configKey = 'sms.general.notifications.new-order.status';
            \Log::info("core()->getConfigData($configKey)".core()->getConfigData($configKey));
            \Log::info("order->status".$order->status);

            if (core()->getConfigData($configKey)) {
                if ($order->status === 'processing') {
                    Notification::route('phone', $order->customer_phone)
                        ->notify(new NewOrderNotification($order));
                }

            }

        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send new order Mail to the customer and admin.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function sendOrderUpdateSms($order)
    {
        //$customerLocale = $this->getLocale($order);

        try {
            $configKey = 'sms.general.notifications.new-order.pattern';
            $notification = null;
            switch ($order->status) {
                case Order::STATUS_CLOSED:
                    $configKey = 'sms.general.notifications.new-refund.pattern';
                    $notification = new OrderRefundNotification($order);
                    break;
                case Order::STATUS_COMPLETED:
                    $configKey = 'sms.general.notifications.completed-order.pattern';
                    $notification = new OrderCompletedNotification($order);
                    break;
                case Order::STATUS_CANCELED:
                    $configKey = 'sms.general.notifications.cancel-order.pattern';
                    $notification = new OrderCancelNotification($order);
                    break;
                case Order::STATUS_PAYMENT_CANCELED:
                    $configKey = 'sms.general.notifications.cancel-order.pattern';
                    $notification = new OrderPaymentCancelNotification($order);
                    break;
            }

            if (!$notification){
                \Log::info("Not sedning notification");
                return;
            }

            \Log::info("core()->getConfigData($configKey)".core()->getConfigData($configKey));
            if (core()->getConfigData($configKey)) {
                Notification::route('phone',$order->customer_phone)
                    ->notify($notification);
            }

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