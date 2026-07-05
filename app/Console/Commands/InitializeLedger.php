<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Organization;
use App\Models\PaymentVoucher;
use App\Models\PurchaseInvoice;
use App\Models\Receipt;
use App\Services\LedgerService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ledger:initialize')]
#[Description('Initialize the Chart of Accounts and post retroactive journal entries for existing transactions')]
class InitializeLedger extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(LedgerService $ledgerService)
    {
        $this->info('Initializing Ledger...');

        $organizations = Organization::all();
        $this->info("Found {$organizations->count()} organizations.");

        foreach ($organizations as $org) {
            $this->info("Initializing standard accounts for Organization ID: {$org->id}");
            $ledgerService->getStandardAccounts($org);
        }

        $this->info('Posting retroactive journal entries for Invoices...');
        $invoices = Invoice::all();
        foreach ($invoices as $invoice) {
            $ledgerService->postInvoice($invoice);
        }
        $this->info("Posted entries for {$invoices->count()} invoices.");

        $this->info('Posting retroactive journal entries for Purchases...');
        $purchases = PurchaseInvoice::all();
        foreach ($purchases as $purchase) {
            $ledgerService->postPurchase($purchase);
        }
        $this->info("Posted entries for {$purchases->count()} purchases.");

        $this->info('Posting retroactive journal entries for Receipts...');
        $receipts = Receipt::all();
        foreach ($receipts as $receipt) {
            $ledgerService->postReceipt($receipt);
        }
        $this->info("Posted entries for {$receipts->count()} receipts.");

        $this->info('Posting retroactive journal entries for Payment Vouchers...');
        $vouchers = PaymentVoucher::all();
        foreach ($vouchers as $voucher) {
            $ledgerService->postPaymentVoucher($voucher);
        }
        $this->info("Posted entries for {$vouchers->count()} payment vouchers.");

        $this->info('Ledger initialization complete!');
    }
}
