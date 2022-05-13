<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Support\Arrayable;

class JeduEloquentUserProvider extends \Illuminate\Auth\EloquentUserProvider
{
    public function validateCredentials(UserContract $user, array $credentials)
    {

        $plain = $credentials['password'];

        return $this->hasher->check($plain, $user->getAuthPassword());
    }
}