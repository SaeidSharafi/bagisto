<?php

namespace App\Providers;

use App\Listeners\Category;
use App\Listeners\OrderListener;
use App\Listeners\Product;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen
        = [
            'sales.order.comment.create.after' => [
                [OrderListener::class, 'sendOrderCommentSms']
            ],
            'checkout.order.save.after' => [
                [OrderListener::class, 'sendNewOrderSms']
            ],
            'sales.order.update-status.after' => [
                OrderListener::class.'@UpdateRegistration',
                OrderListener::class.'@sendNewOrderSms'
            ]

        ];
    // protected $listen = [
    //     Registered::class => [
    //         SendEmailVerificationNotification::class,
    //     ],
    // ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        Event::listen([
            'bagisto.admin.catalog.category.edit_form_accordian.description_images.controls.after',
            'bagisto.admin.catalog.category.create_form_accordian.description_images.controls.after',
        ], function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate(
                'velocity::admin.catelog.categories.category-banner'
            );
        });

        Event::listen([
            'bagisto.admin.catalog.product.edit_form_accordian.images.after',
        ], function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate(
                'velocity::admin.catelog.product.product-banner'
            );
        });

        Event::listen([
            'catalog.category.create.after',
            'catalog.category.update.after',
        ], [Category::class, 'storeCategoryBanner']);

        Event::listen([
            'catalog.product.update.after',
        ], [Product::class, 'storeProductBanner']);

    }
}