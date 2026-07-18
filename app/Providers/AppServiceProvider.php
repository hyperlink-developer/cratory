<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Services\WhatsApp\WhatsAppProviderInterface::class,
            \App\Services\WhatsApp\LogWhatsAppProvider::class
        );

        $this->app->bind(
            \App\Services\GST\GspProviderInterface::class,
            \App\Services\GST\MockGspProvider::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Event::subscribe(
            \App\Listeners\LogNotificationEvent::class
        );

        \App\Models\Invoice::observe(\App\Observers\InvoiceObserver::class);
        \App\Models\PurchaseInvoice::observe(\App\Observers\PurchaseInvoiceObserver::class);
    }
}
