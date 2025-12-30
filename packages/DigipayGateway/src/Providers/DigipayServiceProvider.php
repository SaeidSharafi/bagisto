<?php

declare(strict_types=1);

namespace DigipayGateway\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use DigipayGateway\Contracts\PaymentGatewayInterface;
use DigipayGateway\Contracts\AuthenticatorInterface;
use DigipayGateway\Infrastructure\DigipayClient;
use DigipayGateway\Infrastructure\HttpClient;
use DigipayGateway\Infrastructure\ConfigRepository;
use DigipayGateway\Infrastructure\DigipayAuthenticator;
use DigipayGateway\Services\PaymentOrchestrator;
use DigipayGateway\Services\OrderProcessor;
use DigipayGateway\Services\DeliveryRefundService;
use DigipayGateway\Listeners\OrderStatusListener;
use DigipayGateway\Listeners\RefundListener;

class DigipayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadRoutes();
        $this->loadViews();
        $this->loadTranslations();
        $this->registerConfigurations();
        $this->publishAssets();
        $this->registerEventListeners();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerBindings();
    }

    /**
     * Load package routes.
     *
     * @return void
     */
    protected function loadRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
    }

    /**
     * Load package views.
     *
     * @return void
     */
    protected function loadViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'digipay');
    }

    /**
     * Load package translations.
     *
     * @return void
     */
    protected function loadTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'digipay');
    }

    /**
     * Register package configurations with Bagisto.
     *
     * @return void
     */
    protected function registerConfigurations(): void
    {
        // Register payment method
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/paymentmethods.php',
            'paymentmethods'
        );

        // Register admin system config
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/system.php',
            'core'
        );
    }

    /**
     * Merge package config.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/digipay.php',
            'digipay'
        );
    }

    /**
     * Register interface bindings.
     *
     * @return void
     */
    protected function registerBindings(): void
    {
        // Bind concrete classes as singletons for reuse
        $this->app->singleton(HttpClient::class);
        $this->app->singleton(ConfigRepository::class);

        $this->app->bind(AuthenticatorInterface::class, function ($app) {
            return new DigipayAuthenticator(
                $app->make(HttpClient::class),
                $app->make(ConfigRepository::class)
            );
        });

        $this->app->bind(PaymentGatewayInterface::class, function ($app) {
            return new DigipayClient(
                $app->make(HttpClient::class),
                $app->make(AuthenticatorInterface::class),
                $app->make(ConfigRepository::class)
            );
        });

        // Bind services
        $this->app->bind(OrderProcessor::class, function ($app) {
            return new OrderProcessor(
                $app->make(\Webkul\Sales\Repositories\OrderRepository::class),
                $app->make(\Webkul\Sales\Repositories\InvoiceRepository::class),
                $app->make(\Webkul\Sales\Repositories\OrderTransactionRepository::class)
            );
        });

        $this->app->bind(PaymentOrchestrator::class, function ($app) {
            return new PaymentOrchestrator(
                $app->make(PaymentGatewayInterface::class),
                $app->make(ConfigRepository::class),
                $app->make(\Webkul\Sales\Repositories\OrderRepository::class),
                $app->make(OrderProcessor::class)
            );
        });

        // Bind DeliveryRefundService
        $this->app->bind(DeliveryRefundService::class, function ($app) {
            return new DeliveryRefundService(
                $app->make(PaymentGatewayInterface::class),
                $app->make(\Webkul\Sales\Repositories\OrderRepository::class),
                $app->make(\Webkul\Sales\Repositories\OrderTransactionRepository::class)
            );
        });
    }

    /**
     * Publish package assets.
     *
     * @return void
     */
    protected function publishAssets(): void
    {
        $this->publishes([
            __DIR__ . '/../Config/digipay.php' => config_path('digipay.php'),
        ], 'digipay-config');

        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('views/vendor/digipay'),
        ], 'digipay-views');

        $this->publishes([
            __DIR__ . '/../Resources/lang' => resource_path('lang/vendor/digipay'),
        ], 'digipay-lang');
    }

    /**
     * Register event listeners for order-related events.
     *
     * @return void
     */
    protected function registerEventListeners(): void
    {
        // Note: Delivery confirmation is handled directly in OrderController::complete()
        // This prevents the order from completing if Digipay delivery call fails.

        // Note: Refund is now handled manually via the "Refund via Digipay" button
        // in the admin order page (resources/admin-themes/default/views/sales/orders/view.blade.php)
        // via @include('digipay::admin.orders.digipay-actions')
    }
}
