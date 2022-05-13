<?php

namespace App\Providers;

use Closure;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class JeduPasswordBroker extends \Illuminate\Auth\Passwords\PasswordBroker
{

    /**
     * Validate a password reset for the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\CanResetPassword|string
     */
    public function getToken(array $credentials)
    {
        if (is_null($user = $this->getUser($credentials))) {
            return static::INVALID_USER;
        }
        return $this->tokens->get($user);
    }

    /**
     * Reset the password for the given token.
     *
     * @param  array  $credentials
     * @param  Closure  $callback
     * @return mixed
     */
    public function reset(array $credentials, Closure $callback)
    {

        $user = $this->validateReset($credentials);

        // If the responses from the validate method is not a user instance, we will
        // assume that it is a redirect and simply return it from this method and
        // the user is properly redirected having an error message on the post.
        if (! $user instanceof CanResetPasswordContract) {
            return $user;
        }

        $password = $credentials['password'];

        // Once the reset has been validated, we'll call the given callback with the
        // new password. This gives the user an opportunity to store the password
        // in their persistent storage. Then we'll delete the token and return.
        $callback($user, $password);

        $this->tokens->delete($user);

        return static::PASSWORD_RESET;
    }
}