<?php

use Illuminate\Support\Facades\Route;
use DigipayGateway\Http\Controllers\DigipayController;

/*
|--------------------------------------------------------------------------
| Digipay Gateway Routes
|--------------------------------------------------------------------------
|
| Routes for the Digipay payment gateway.
|
*/

Route::group([
    'middleware' => ['web'],
], function () {
    // Redirect to payment gateway
    Route::get('digipay/redirect', [DigipayController::class, 'redirect'])
        ->name('digipay.redirect');

    // User cancelled payment
    Route::get('digipay/cancel', [DigipayController::class, 'cancel'])
        ->name('digipay.cancel');

    // Payment failed
    Route::get('digipay/failed', [DigipayController::class, 'failed'])
        ->name('digipay.failed');
});

// Callback route - outside web middleware to avoid CSRF issues
// Digipay will POST to this endpoint
Route::post('digipay/callback', [DigipayController::class, 'callback'])
    ->name('digipay.callback')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
