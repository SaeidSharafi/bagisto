<?php

use MellatGateway\Http\Controllers\MellatController;

Route::group(['middleware' => ['web']], function () {
    Route::prefix('mellat')->group(function () {
        Route::get('/redirect', [MellatController::class, 'redirect'])->name('mellat.redirect');

        Route::get('/cancel', [MellatController::class, 'cancel'])->name('mellat.cancel');

        Route::get('/failed', [MellatController::class, 'failed'])->name('mellat.failed');
    });


});
Route::prefix('mellat')->group(function () {
    Route::post('/verify', [MellatController::class, 'callback'])
        ->name('mellat.callback');
});
