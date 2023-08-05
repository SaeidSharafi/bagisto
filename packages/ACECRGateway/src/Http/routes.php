<?php

use ACECRGateway\Http\Controllers\ACECRController;

Route::group(['middleware' => ['web']], function () {
    Route::prefix('acecr')->group(function () {
        Route::get('/redirect', [ACECRController::class, 'redirect'])->name('acecr.redirect');

        Route::get('/cancel', [ACECRController::class, 'cancel'])->name('acecr.cancel');

        Route::get('/failed', [ACECRController::class, 'failed'])->name('acecr.failed');
    });


});
Route::prefix('acecr')->group(function () {
    Route::post('/verify', [ACECRController::class, 'callback'])
        ->name('acecr.callback');
});
