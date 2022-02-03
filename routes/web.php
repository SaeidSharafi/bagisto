<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/**
 * Cart merger middleware. This middleware will take care of the items
 * which are deactivated at the time of buy now functionality. If somehow
 * user redirects without completing the checkout then this will merge
 * full cart.
 *
 * If some routes are not able to merge the cart, then place the route in this
 * group.
 */

use App\Http\Controllers\Shop\API\JeduShopController;
use App\Http\Controllers\Shop\Customer\JeduCustomerController;
use App\Http\Controllers\Shop\Customer\JeduLoginRegistrationController;
use App\Http\Controllers\Shop\Customer\JeduSessionController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']],
    function () {

        Route::group(['middleware' => ['cart.merger']], function () {
            /**
             * Customer routes.
             */
            Route::prefix('customer')->group(function () {

                /**
                 * Login & register routes.
                 */
                Route::get('login-register',
                    [JeduLoginRegistrationController::class,'show'])
                    ->defaults('_config', [
                        'view' => 'shop::customers.auth.index'
                    ])->name('customer.auth.create');



                // Login form.
                Route::get('login', [JeduSessionController::class,'show'])->defaults('_config', [
                    'view' => 'shop::customers.session.index',
                    'redirect_verify' => 'customer.sms.verify.show'
                ])->name('customer.session.index');

                // Login.
                Route::post('login',  [JeduSessionController::class,'create'])->defaults('_config', [
                    'redirect' => 'customer.profile.index',
                ])->name('customer.session.create');

                /**
                 * Registration routes.
                 */
                //// Show registration form.
                Route::get('register',
                    static function (){
                        return redirect()->route('customer.auth.create');
                    }
                )->name('customer.register.show');

                // Store new registered user.
                Route::post('register',
                    [JeduLoginRegistrationController::class,'store'])
                    ->defaults('_config', [
                        'redirect'        => 'customer.session.index',
                        'redirect_verify' => 'customer.sms.verify.show',
                    ])->name('customer.register.create');

                // Verify account.
                Route::get('register/confirm/{token}',
                    [JeduLoginRegistrationController::class,'showverfiy'])
                    ->defaults('_config', [
                        'view' => 'shop::customers.signup.verify'
                    ])
                    ->name('customer.sms.verify.show');

                // Verify account.
                Route::post('register/confirm/',
                    [JeduLoginRegistrationController::class,'verifyAccountWithSMS'])
                    ->defaults('_config', [
                        'redirect_on_fail' => 'customer.sms.verify.show'
                    ])
                    ->name('customer.sms.verify.complete');

                /**
                 * Customer account. All the below routes are related to
                 * customer account details.
                 */
                Route::prefix('account')->group(function () {
                    Route::get('profile/edit', [JeduCustomerController::class,'edit'])->defaults('_config', [
                        'view' => 'shop::customers.account.profile.edit'
                    ])->name('customer.profile.edit');

                    Route::post('profile/edit', [JeduCustomerController::class,'update'])->defaults('_config', [
                        'redirect' => 'customer.profile.index'
                    ])->name('customer.profile.store');
                });

                //Route::redirect('login',route('customer.register.index'));
            });


            Route::get('/category-details',
                [JeduShopController::class, 'categoryDetails'])
                ->name('velocity.category.details');
        });
    });