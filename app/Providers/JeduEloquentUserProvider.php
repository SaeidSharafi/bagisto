<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class JeduEloquentUserProvider extends \Illuminate\Auth\EloquentUserProvider
{
    public function validateCredentials(UserContract $user, array $credentials)
    {
        if (array_key_exists('otp',$credentials)){
            return true;
        }

        $plain = $credentials['password'];

        return $this->hasher->check($plain, $user->getAuthPassword());
    }
}