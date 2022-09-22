<?php

use Illuminate\Support\Facades\Route;
use Webkul\Notification\Http\Controllers\Admin\NotificationController;

/**
 * Notification routes.
 */
Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {
    Route::get('notifications', [NotificationController::class, 'index'])->defaults('_config', [
        'view' => 'admin::notifications.index',
    ])->name('admin.notification.index');

    Route::get('get-notifications', [NotificationController::class, 'getNotifications'])
        ->name('admin.notification.get-notification');

    Route::get('viewed-notifications/{orderId}', [NotificationController::class, 'viewedNotifications'])
        ->name('admin.notification.viewed-notification');

    // read one notification
    Route::post('read-one-notifications', [NotificationController::class, 'readNotification'])
        ->name('admin.notification.read-one');

    // read one notification
    Route::post('read-one-notifications-index', [NotificationController::class, 'readNotificationIndex'])
        ->name('admin.notification.read-one-index');


    // read all notification
    Route::post('read-all-notifications', [NotificationController::class, 'readAllNotifications'])
        ->name('admin.notification.read-all');
});
