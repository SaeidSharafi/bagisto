<?php

namespace App\Listeners;

use App\Jobs\UpdateRegisteration;
use App\Services\MoodleService;
use App\Services\SpotPlayerService;
use App\Traits\Sms;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Models\Order;

class OrderListener
{
    use Sms;

    public function UpdateRegistration($order)
    {
        //$request = new HttpRequestService($data);
        //$response = $request->build();

        MoodleService::updateUserEnrolment($order);
        UpdateRegisteration::dispatch($order);
    }

    public function UpdateSpotLicense($order)
    {
        Log::info("UpdateSpotLicense order status: {$order->status}");
        if ($order->status === Order::STATUS_COMPLETED) {
            Log::info("UpdateSpotLicense",$order->toArray());
            foreach ($order->items as $item) {
                if ($item->product->spot_id) {
                    Log::info("UpdateSpotLicense: {$item->product->spot_id}");
                    $result = SpotPlayerService::generateLicense($order, $item);
                }
            }
        }
    }
}