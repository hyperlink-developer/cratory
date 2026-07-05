<?php

use App\Http\Middleware\EnsureOrganizationSelected;
use App\Http\Middleware\SetCurrentOrganization;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Cratory
|--------------------------------------------------------------------------
*/

// Public routes
Route::view('/', 'welcome')->name('welcome');
Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy-policy');
Route::view('/terms-of-service', 'pages.terms-of-service')->name('terms-of-service');
Route::view('/contact-support', 'pages.contact-support')->name('contact-support');

// Auth routes are handled by Fortify

// Authenticated routes
Route::middleware(['auth', SetCurrentOrganization::class])->group(function () {

    // Onboarding (no org required)
    Route::get('/onboarding', \App\Livewire\Onboarding\OrganizationWizard::class)
        ->name('onboarding.wizard');

    // Profile Settings
    Route::get('/profile', \App\Livewire\Profile\ProfileForm::class)
        ->name('profile');

    // All routes requiring an active organization
    Route::middleware([EnsureOrganizationSelected::class])->group(function () {

        // Dashboard
        Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard');

        // Invoices (Sales & Service)
        Route::get('/invoices', \App\Livewire\Invoices\InvoiceList::class)->name('invoices.index');
        Route::get('/invoices/create', \App\Livewire\Invoices\InvoiceForm::class)->name('invoices.create');
        Route::get('/invoices/{invoice}/edit', \App\Livewire\Invoices\InvoiceForm::class)->name('invoices.edit');
        Route::get('/invoices/{invoice}/pdf', [\App\Http\Controllers\PdfController::class, 'downloadInvoice'])->name('invoices.pdf');

        // Contacts
        Route::get('/contacts', \App\Livewire\Contacts\ContactList::class)->name('contacts.index');
        Route::get('/contacts/create', \App\Livewire\Contacts\ContactForm::class)->name('contacts.create');
        Route::get('/contacts/{contact}/edit', \App\Livewire\Contacts\ContactForm::class)->name('contacts.edit');

        // Inventory
        Route::get('/inventory', \App\Livewire\Inventory\ProductList::class)->name('inventory.index');
        Route::get('/inventory/create', \App\Livewire\Inventory\ProductForm::class)->name('inventory.create');
        Route::get('/inventory/{product}/edit', \App\Livewire\Inventory\ProductForm::class)->name('inventory.edit');

        // Purchases
        Route::get('/purchases', \App\Livewire\Purchases\PurchaseList::class)->name('purchases.index');
        Route::get('/purchases/create', \App\Livewire\Purchases\PurchaseForm::class)->name('purchases.create');
        Route::get('/purchases/{purchase}/edit', \App\Livewire\Purchases\PurchaseForm::class)->name('purchases.edit');

        // Receipts
        Route::get('/receipts', \App\Livewire\Finance\ReceiptList::class)->name('receipts.index');
        Route::get('/receipts/create', \App\Livewire\Finance\ReceiptForm::class)->name('receipts.create');

        // Payment Vouchers
        Route::get('/vouchers', \App\Livewire\Finance\VoucherList::class)->name('vouchers.index');
        Route::get('/vouchers/create', \App\Livewire\Finance\VoucherForm::class)->name('vouchers.create');

        // Settings
        Route::get('/settings/tax-rates', \App\Livewire\Settings\TaxRates::class)->name('settings.tax-rates');
        Route::get('/settings/invoice-templates', \App\Livewire\Settings\InvoiceTemplates::class)->name('settings.invoice-templates');

        // Logout
        Route::post('/logout', function () {
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect('/');
        })->name('logout');
    });
});
