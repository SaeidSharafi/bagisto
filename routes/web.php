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

use App\Http\Controllers\Admin\CmsCategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ResetCustomerPasswordController;
use App\Http\Controllers\MyCourseController;
use App\Http\Controllers\Shop\API\JeduShopController;
use App\Http\Controllers\Shop\Customer\JeduCustomerController;
use App\Http\Controllers\Shop\Customer\JeduForgotPassword;
use App\Http\Controllers\Shop\Customer\JeduLoginRegistrationController;
use App\Http\Controllers\Shop\Customer\JeduResetPasswordController;
use App\Http\Controllers\Shop\Customer\JeduSessionController;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Webkul\Admin\Http\Controllers\Sales\OrderController;
use Webkul\Shop\Http\Controllers\OnepageController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']],
    function () {

        Route::get('/checkout/get-pyaments', [OnepageController::class, 'getPaymentMethods'])
            ->name('shop.checkout.get-pyaments');

        Route::group(['middleware' => ['cart.merger']], function () {

            Route::view('/about-us', 'shop.aboutus')
                ->name('shop.aboutus');

            /**
             * Customer routes.
             */
            Route::prefix('customer')->group(function () {

                /**
                 * Login & register routes.
                 */
                Route::get('login-register',
                    [JeduLoginRegistrationController::class, 'show'])
                    ->defaults('_config', [
                        'view' => 'shop::customers.auth.index'
                    ])->name('customer.auth.create');

                // Login form.
                Route::get('login', [JeduSessionController::class, 'show'])->defaults('_config', [
                    'view'            => 'shop::customers.session.index',
                    'redirect_verify' => 'customer.sms.verify.show'
                ])->name('customer.session.index');

                // Login.
                Route::post('login', [JeduSessionController::class, 'create'])->defaults('_config', [
                    'redirect' => 'customer.moodle.index',
                ])->middleware('web_throttle:10,1')->name('customer.session.create');

                /**
                 * Registration routes.
                 */
                //// Show registration form.
                Route::get('register',
                    static function () {
                        return redirect()->route('customer.auth.create');
                    }
                )->name('customer.register.show');

                // Store new registered user.
                Route::post('register',
                    [JeduLoginRegistrationController::class, 'store'])
                    ->defaults('_config', [
                        'redirect'        => 'customer.session.index',
                        'redirect_verify' => 'customer.sms.verify.show',
                    ])->name('customer.register.create');

                // Verify account.
                Route::get('register/confirm/{token}',
                    [JeduLoginRegistrationController::class, 'showVerify'])
                    ->defaults('_config', [
                        'view' => 'shop::customers.signup.verify'
                    ])->name('customer.sms.verify.show');

                // Verify account.
                Route::post('register/confirm/{token}',
                    [JeduLoginRegistrationController::class, 'verifyAccountWithSMS'])
                    ->defaults('_config', ['redirect_on_fail' => 'customer.sms.verify.show'])
                    ->middleware('web_throttle:10,1')
                    ->name('customer.sms.verify.complete');

                /**
                 * Forgot password routes.
                 */
                Route::get('/forgot-password', [JeduForgotPassword::class, 'create'])->defaults('_config', [
                    'view' => 'shop::customers.signup.forgot-password',
                ])->name('customer.forgot-password.create');

                Route::get('/forgot-password/confirm/{token}', [JeduForgotPassword::class, 'showVerify'])
                    ->defaults('_config', ['view' => 'shop::customers.signup.forgot-password-otp'])
                    ->name('customer.forgot-password.verify.show');

                Route::post('register/confirm/', [JeduForgotPassword::class, 'verify'])
                    ->defaults('_config', ['redirect_on_fail' => 'customer.forgot-password.verify.show'])
                    ->middleware('web_throttle:10,1')
                    ->name('customer.forgot-password.verify');

                Route::post('/forgot-password', [JeduForgotPassword::class, 'store'])
                    ->name('customer.forgot-password.store');

                Route::get('/reset-password/{token}', [JeduResetPasswordController::class, 'create'])
                    ->defaults('_config', [
                        'view' => 'shop::customers.signup.reset-password',
                    ])->name('customer.reset-password.create');

                Route::post('/reset-password', [JeduResetPasswordController::class, 'store'])->defaults('_config', [
                    'redirect' => 'customer.profile.index',
                ])->name('customer.reset-password.store');

                /**
                 * Customer account. All the below routes are related to
                 * customer account details.
                 */
                Route::prefix('account')->group(function () {
                    Route::get('profile/edit', [JeduCustomerController::class, 'edit'])->defaults('_config', [
                        'view' => 'shop::customers.account.profile.edit'
                    ])->name('customer.profile.edit');

                    Route::post('profile/edit', [JeduCustomerController::class, 'update'])->defaults('_config', [
                        'redirect' => 'customer.profile.index'
                    ])->name('customer.profile.store');

                    /**
                     * Moodle
                     */
                    Route::get('moodle', [MyCourseController::class, 'index'])->defaults('_config', [
                        'view' => 'shop::customers.account.profile.index'
                    ])->name('customer.moodle.index');

                    Route::get('moodle/redirect', [MyCourseController::class, 'redirectToCourse'])
                        ->name('customer.moodle.redirect');

                    /**
                     * SpotPlayer
                     */
                    Route::get('vc/{spotLicense}', [MyCourseController::class, 'redirectToSpotPlayer'])
                        ->defaults('_config', [
                            'view' => 'shop::customers.account.profile.index'
                        ])->name('customer.spot.player');

                });

                //Route::redirect('login',route('customer.register.index'));
            });

            Route::get('/category-details',
                [JeduShopController::class, 'categoryDetails'])
                ->name('velocity.category.details');

            Route::get('/category-products/{categoryId}', [JeduShopController::class, 'getCategoryProducts'])
                ->name('velocity.category.products');
        });


        Route::group(['middleware' => ['cart.merger']], function () {
            /**
             * CMS pages.
             */
            //Route::get('page/{category}/{url_key}',
            //    [\Webkul\CMS\Http\Controllers\Shop\PagePresenterController::class, 'presenter'])->name('shop.cms.page');
        });
    });
Route::group(['middleware' => ['web', 'admin', 'admin_locale'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('sales')->group(function () {
        Route::prefix('orders')->group(function () {
            Route::get('complete/{id}', [OrderController::class, 'complete'])
                ->name('admin.sales.orders.complete');
                Route::get('sync-ims/{id}', [OrderController::class, 'syncIms'])
                ->name('admin.sales.orders.sync-ims');

            Route::get('/upload', [\App\Http\Controllers\Admin\OrderController::class, 'index'])
                ->name('admin.sales.order.bulk.index');
            Route::post('/upload', [\App\Http\Controllers\Admin\OrderController::class, 'uploadCSV'])
                ->name('admin.sales.order.bulk.upload');

        });

    });
    Route::get('moodle/redirect', [\App\Http\Controllers\Admin\AdminController::class, 'redirectMoodle'])
        ->name('admin.moodle.redirect');

    Route::get('sms', [\App\Http\Controllers\Admin\SmsController::class, 'index'])
        ->name('admin.sms.index');

    Route::prefix('blog')->group(function () {
        Route::prefix('category')->group(function () {
            Route::get('/', [CmsCategoryController::class, 'index'])->defaults('_config', [
                'view' => 'admin.cms.category.index',
            ])->name('admin.cms.category.index');

            Route::get('create', [CmsCategoryController::class, 'create'])->defaults('_config', [
                'view' => 'admin.cms.category.create',
            ])->name('admin.cms.category.create');

            Route::post('create', [CmsCategoryController::class, 'store'])->defaults('_config', [
                'redirect' => 'admin.cms.category.index',
            ])->name('admin.cms.category.store');

            Route::get('edit/{id}', [CmsCategoryController::class, 'edit'])->defaults('_config', [
                'view' => 'admin.cms.category.edit',
            ])->name('admin.cms.category.edit');

            Route::post('edit/{id}', [CmsCategoryController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.cms.category.index',
            ])->name('admin.cms.category.update');

            Route::post('/delete/{id}', [CmsCategoryController::class, 'delete'])->defaults('_config', [
                'redirect' => 'admin.cms.category.index',
            ])->name('admin.cms.category.delete');

            Route::post('/massdelete', [CmsCategoryController::class, 'massDelete'])->defaults('_config', [
                'redirect' => 'admin.cms.category.index',
            ])->name('admin.cms.category.mass-delete');
        });
    });

    Route::prefix('customers')->group(function () {
        Route::post('/reset-password/{id}', ResetCustomerPasswordController::class)
            ->defaults('_config', [
                'redirect' => 'admin.customer.index',
            ])->name('admin.customers.reset-password');

        Route::get('/upload', [CustomerController::class, 'index'])
            ->name('admin.customers.bulk.index');
        Route::post('/upload', [CustomerController::class, 'uploadCSV'])
            ->name('admin.customers.bulk.upload');
    });

});

Breadcrumbs::for('customer.moodle.index', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.profile.index');

    $trail->push(trans('app.customer.account.moodle.index.page-title'), route('customer.moodle.index'));
});
