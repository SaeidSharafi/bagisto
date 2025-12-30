<?php

use Illuminate\Support\Facades\Route;
use DigipayGateway\Http\Controllers\DigipayController;
use DigipayGateway\Http\Controllers\DigipayAdminController;

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

/*
|--------------------------------------------------------------------------
| Admin Routes for Digipay
|--------------------------------------------------------------------------
|
| Routes for admin panel to manage delivery and refunds.
|
*/

Route::group([
    'prefix' => 'admin/digipay',
    'middleware' => ['web', 'admin'],
], function () {
    // Get payment info for an order
    Route::get('orders/{orderId}/payment-info', [DigipayAdminController::class, 'getPaymentInfo'])
        ->name('admin.digipay.payment-info');

    // Confirm delivery for CREDIT/BNPL orders
    Route::post('orders/{orderId}/deliver', [DigipayAdminController::class, 'confirmDelivery'])
        ->name('admin.digipay.deliver');

    // Refund a payment
    Route::post('orders/{orderId}/refund', [DigipayAdminController::class, 'refund'])
        ->name('admin.digipay.refund');

    // Check refund status
    Route::get('refunds/inquire', [DigipayAdminController::class, 'inquireRefund'])
        ->name('admin.digipay.refund-inquire');
});
