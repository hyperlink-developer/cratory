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
Route::view('/integrations', 'pages.integrations')->name('integrations');
Route::view('/changelog', 'pages.changelog')->name('changelog');
Route::view('/help-center', 'pages.help-center')->name('help-center');

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

        // Accounting
        Route::get('/accounting/chart-of-accounts', \App\Livewire\Accounting\ChartOfAccounts::class)->name('accounting.chart-of-accounts');
        Route::get('/accounting/manual-journal', \App\Livewire\Accounting\ManualJournal::class)->name('accounting.manual-journal');

        // Reports
        Route::get('/reports/sales', \App\Livewire\Reports\SaleReport::class)->name('reports.sales');
        Route::get('/reports/purchases', \App\Livewire\Reports\PurchaseReport::class)->name('reports.purchases');
        Route::get('/reports/profit-loss', \App\Livewire\Reports\ProfitLossReport::class)->name('reports.profit-loss');
        Route::get('/reports/trial-balance', \App\Livewire\Reports\TrialBalance::class)->name('reports.trial-balance');
        Route::get('/reports/balance-sheet', \App\Livewire\Reports\BalanceSheet::class)->name('reports.balance-sheet');
        
        // Report Exports
        Route::get('/reports/sales/export/pdf', [\App\Http\Controllers\ReportExportController::class, 'exportSalesPdf'])->name('reports.sales.pdf');
        Route::get('/reports/sales/export/csv', [\App\Http\Controllers\ReportExportController::class, 'exportSalesCsv'])->name('reports.sales.csv');
        Route::get('/reports/purchases/export/pdf', [\App\Http\Controllers\ReportExportController::class, 'exportPurchasesPdf'])->name('reports.purchases.pdf');
        Route::get('/reports/purchases/export/csv', [\App\Http\Controllers\ReportExportController::class, 'exportPurchasesCsv'])->name('reports.purchases.csv');
        Route::get('/reports/profit-loss/export/pdf', [\App\Http\Controllers\ReportExportController::class, 'exportProfitLossPdf'])->name('reports.profit-loss.pdf');
        Route::get('/reports/trial-balance/export/pdf', [\App\Http\Controllers\ReportExportController::class, 'exportTrialBalancePdf'])->name('reports.trial-balance.pdf');
        Route::get('/reports/balance-sheet/export/pdf', [\App\Http\Controllers\ReportExportController::class, 'exportBalanceSheetPdf'])->name('reports.balance-sheet.pdf');

        Route::get('/settings/tax-rates', \App\Livewire\Settings\TaxRates::class)->name('settings.tax-rates');
        Route::get('/settings/unit-of-measures', \App\Livewire\Settings\UnitOfMeasures::class)->name('settings.uoms');
        Route::get('/settings/invoice-templates', \App\Livewire\Settings\InvoiceTemplates::class)->name('settings.invoice-templates');
        Route::get('/settings/document-numbering', \App\Livewire\Settings\DocumentNumbering::class)->name('settings.document-numbering');
        Route::get('/settings/users', \App\Livewire\Settings\UserManagement::class)->name('settings.users');

        // Logout
        Route::post('/logout', function () {
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect('/');
        })->name('logout');
    });
});
