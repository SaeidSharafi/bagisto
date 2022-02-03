<?php

use PayIr\Http\Controllers\PayDotIrController;

Route::group(['middleware' => ['web']], function () {
    Route::prefix('pay_ir')->group(function () {
        Route::get('/redirect', [PayDotIrController::class,'redirect'])->name('paydotir.redirect');

        Route::get('/verify', [PayDotIrController::class, 'callback'])->name('paydotir.callback');

        Route::get('/cancel', [PayDotIrController::class,'cancel'])->name('paydotir.cancel');

        Route::get('/failed', [PayDotIrController::class,'failed'])->name('paydotir.failed');
    });


});

