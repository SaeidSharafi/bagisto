<?php

namespace App\Services;

use App\Models\SpotLicense;
use Illuminate\Support\Facades\Http;
use Webkul\Sales\Models\Order;

class SpotService
{
    protected const API_ENDPOINT = "https://panel.spotplayer.ir/license/edit/";

    public static function generateLicense($order)
    {
        $order->update(['status' => Order::STATUS_PROCESSING]);
        $data = [];
        $courses = $order->items->map(function ($item) use (&$data) {
            return $item->product->spot_id;
        });
        $data['course'] = $courses->toArray();
        $data['name'] = $order->customer_first_name." ".$order->customer_last_name;
        $data['payload'] = $order->increment_id;
        $data['test'] = config('app.spot_player.sandbox');
        $data["watermark"] = ["texts" => [["text" => $order->customer_phone]]];
        //dd(Http::withBody(json_encode($data, JSON_THROW_ON_ERROR), 'application/json'));
        try {
            $response = Http::withBody(json_encode($data, JSON_THROW_ON_ERROR), 'application/json')
                ->withHeaders([
                    '$API' => 'Y2uYSI8uD5GiNK856IfQ7QOugwQwj00='
                ])
                ->post(self::API_ENDPOINT)
                ->throw()
                ->json();
            $response['order_id'] = $order->id;
        } catch (\Exception $exception) {
            report($exception);
            return;
        }

        SpotLicense::create($response);

    }
}