<?php

namespace App\Services;

use App\Models\SpotLicense;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Models\Order;

class SpotService
{
    protected const API_ENDPOINT = "https://panel.spotplayer.ir/license/edit/";

    public static function generateLicense($order, $order_item)
    {
        //$order->update(['status' => Order::STATUS_PROCESSING]);

        $courses = $order->items->map(function ($item) {
            return $item->product->spot_id;
        });

        $data = [];
        $data['course'] = $courses->toArray();
        $data['name'] = $order->customer_first_name." ".$order->customer_last_name;
        $data['payload'] = $order->increment_id;
        $data['test'] = config('app.spot_player.sandbox');
        $data["watermark"] = ["texts" => [["text" => $order->customer_phone]]];

        $api_key = config('app.spot_player.api_key');
        if (!$api_key) {
            Log::error('please set SpotPlayer API key first');
            return;
        }

        try {
            $response = Http::withBody(json_encode($data, JSON_THROW_ON_ERROR), 'application/json')
                ->withHeaders([
                    '$API' => $api_key
                ])
                ->post(self::API_ENDPOINT)
                ->throw()
                ->json();
            $response['order_id'] = $order->id;
            $response['product_id'] = $order_item->product_id;
        } catch (\Exception $exception) {
            report($exception);
            return;
        }

        SpotLicense::create($response);

    }

    public static function formatProduct(Collection $products, $customer)
    {

        return $products->map(function ($product) use ($customer) {
            //$item['id'] = $product->spot_id;
            $item['moodle_url'] = route('customer.spot.player',$product->spot_id);
            $item['fullname'] = $product->short_name;
            $item['image'] = productimage()->getProductBaseImage($product)['medium_image_url'];
            return $item;
        });
    }
}