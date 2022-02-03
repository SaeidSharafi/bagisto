<?php

use App\Http\Controllers\Shop\API\JeduCustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['locale', 'theme', 'currency']], function () {

    Route::get('customer/{phone}',
        [JeduCustomerController::class, 'getCustmerRemainingTime'])
        ->defaults('_config', [
            'repository'             => 'Webkul\Customer\Repositories\CustomerRepository',
            'resource'               => 'App\Http\Resources\JeduCustomer',
            'authorization_required' => true
        ]);
    Route::get('customer-sms-remaining/{phone}',
        [JeduCustomerController::class, 'getCustmerRemainingTime'])
        ->defaults('_config', [
            'repository' => 'Webkul\Customer\Repositories\CustomerRepository',
            'resource'   => 'App\Http\Resources\JeduCustomerSMSRemaining'
        ]);
    Route::post('resend-sms', [JeduCustomerController::class, 'resendSms'])
        ->defaults('_config', [
            'repository' => 'Webkul\Customer\Repositories\CustomerRepository',
            'resource'   => 'App\Http\Resources\JeduCustomerSMSRemaining'
        ]);
});
