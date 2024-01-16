<?php

namespace App\Services;

use App\Models\SpotLicense;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Repositories\OrderRepository;


class SpotPlayerService
{
    protected const API_ENDPOINT = "https://panel.spotplayer.ir/license/edit/";

    public static function generateLicense($order, $order_item)
    {
        //$order->update(['status' => Order::STATUS_PROCESSING]);
        $license = SpotLicense::query()
            ->where('order_id', $order->id)
            ->where('product_id', $order_item->product_id)
            ->exists();
        if ($license && !config('app.spot_player.sandbox')) {
            Log::error('License Already Exit');
            return;
        }
        $courses = $order->items->map(function ($item) {
            return $item->product->spot_id;
        });

        $data = [];
        $data['course'] = $courses->toArray();
        $data['name'] = $order->customer_first_name." ".$order->customer_last_name;
        $data['payload'] = $order->increment_id;
        $data['test'] = config('app.spot_player.sandbox');
        $data["watermark"] = [
            "texts" => [
                ["text" => $order->increment_id],
                ["text" => $order->customer?->national_code],
                ["text" => $order->customer?->phone],
            ]
        ];

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
        if ($order->spot_license === null) {
            $order->spot_license()->create($response);
        } else {
            $order->spot_license->update($response);
        }

    }

    public static function formatProduct(Collection $products, $customer)
    {

        return $products->map(function ($product) use ($customer) {
            //$item['id'] = $product->spot_id;
            $item['url'] = route('customer.spot.player', $product->spot_id);
            $item['fullname'] = $product->short_name;
            $item['image'] = productimage()->getProductBaseImage($product)['medium_image_url'];
            return $item;
        });
    }
}