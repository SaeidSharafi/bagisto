<?php

namespace App\Services;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Models\Order;

class ImsApiService
{

    protected static function getApiKey()
    {
        return config('app.ims.api_key');
    }

    public static function isTeacher($phone)
    {
        return Cache::remember('is_teacher'.auth('customer')->id(), 60, function () use ($phone) {

            $apiKey = self::getApiKey();

            if (!$apiKey){
                return false;
            }

            $url = config('app.ims.base_url').'/api/v1/teachers/is-teacher';

            $response = Http::withToken($apiKey)
                ->asForm()
                ->post($url, [
                    'username' => $phone
                ]);

            if ($response->ok()) {
                return  $response->json('is_teacher');
            }
            if ($response->status() === 404) {
                Log::error('teacher not found: \n'.$phone);
            }
            if ($response->unauthorized()) {
                Log::error('IMS Auth failed: \n'.$response->json('message'));
            }
            if ($response->failed()) {
                Log::error('getting teacher from IMS failed: \n'.$response->body());
            }
            return false;
        });
    }

    public static function getMagicLink($phone)
    {
        $apiKey = self::getApiKey();
        if (!$apiKey){
            return false;
        }

        $url = config('app.ims.base_url').'/api/v1/auth/magic-link';

        $response = Http::withToken($apiKey)
            ->asForm()
            ->post($url, [
                'username' => $phone
            ]);

        if ($response->ok()) {
            return $response->json('url');
        }
        if ($response->status() === 404) {
            Log::error('user not found: \n'.$phone);
        }
        if ($response->unauthorized()) {
            Log::error('IMS Auth failed: \n'.$response->json('message'));
        }
        if ($response->failed()) {
            Log::error('getting teacher from IMS failed: \n'.$response->body());
        }

        return false;
    }
}