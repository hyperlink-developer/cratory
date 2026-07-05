<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\Organization;
use App\Models\PurchaseInvoice;
use App\Models\PaymentVoucher;
use App\Models\Receipt;
use Illuminate\Support\Facades\DB;

class LedgerService
{
    /**
     * Ensure standard accounts exist for an organization and return an array of them.
     */
    public function getStandardAccounts(Organization $organization)
    {
        // 1. Assets
        $currentAssetsGroup = AccountGroup::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '1000'],
            ['name' => 'Current Assets', 'type' => 'asset', 'is_system' => true]
        );

        $cashAccount = Account::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '1010'],
            ['account_group_id' => $currentAssetsGroup->id, 'name' => 'Cash on Hand', 'is_system' => true]
        );

        $bankAccount = Account::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '1020'],
            ['account_group_id' => $currentAssetsGroup->id, 'name' => 'Bank Account', 'is_system' => true]
        );

        $arAccount = Account::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '1100'],
            ['account_group_id' => $currentAssetsGroup->id, 'name' => 'Accounts Receivable', 'is_system' => true]
        );
        
        $inventoryAccount = Account::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '1200'],
            ['account_group_id' => $currentAssetsGroup->id, 'name' => 'Inventory', 'is_system' => true]
        );

        $taxReceivableAccount = Account::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '1300'],
            ['account_group_id' => $currentAssetsGroup->id, 'name' => 'Tax Receivable (Input Tax)', 'is_system' => true]
        );

        // 2. Liabilities
        $currentLiabilitiesGroup = AccountGroup::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '2000'],
            ['name' => 'Current Liabilities', 'type' => 'liability', 'is_system' => true]
        );

        $apAccount = Account::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '2100'],
            ['account_group_id' => $currentLiabilitiesGroup->id, 'name' => 'Accounts Payable', 'is_system' => true]
        );

        $taxPayableAccount = Account::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '2200'],
            ['account_group_id' => $currentLiabilitiesGroup->id, 'name' => 'Tax Payable (Output Tax)', 'is_system' => true]
        );

        // 3. Equity
        $equityGroup = AccountGroup::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '3000'],
            ['name' => 'Equity', 'type' => 'equity', 'is_system' => true]
        );

        $ownersEquityAccount = Account::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '3100'],
            ['account_group_id' => $equityGroup->id, 'name' => 'Owner\'s Equity / Retained Earnings', 'is_system' => true]
        );

        // 4. Revenue
        $revenueGroup = AccountGroup::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '4000'],
            ['name' => 'Revenue', 'type' => 'revenue', 'is_system' => true]
        );

        $salesAccount = Account::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '4100'],
            ['account_group_id' => $revenueGroup->id, 'name' => 'Sales Revenue', 'is_system' => true]
        );
        
        $serviceAccount = Account::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '4200'],
            ['account_group_id' => $revenueGroup->id, 'name' => 'Service Revenue', 'is_system' => true]
        );

        // 5. Expenses
        $expenseGroup = AccountGroup::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '5000'],
            ['name' => 'Expenses', 'type' => 'expense', 'is_system' => true]
        );

        $cogsAccount = Account::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '5100'],
            ['account_group_id' => $expenseGroup->id, 'name' => 'Cost of Goods Sold', 'is_system' => true]
        );
        
        $discountAccount = Account::firstOrCreate(
            ['organization_id' => $organization->id, 'code' => '5200'],
            ['account_group_id' => $expenseGroup->id, 'name' => 'Discounts Given', 'is_system' => true]
        );

        return [
            'cash' => $cashAccount,
            'bank' => $bankAccount,
            'ar' => $arAccount,
            'inventory' => $inventoryAccount,
            'tax_receivable' => $taxReceivableAccount,
            'ap' => $apAccount,
            'tax_payable' => $taxPayableAccount,
            'equity' => $ownersEquityAccount,
            'sales' => $salesAccount,
            'service' => $serviceAccount,
            'cogs' => $cogsAccount,
            'discount' => $discountAccount,
        ];
    }

    /**
     * Post journal entry for a Sales or Service Invoice
     */
    public function postInvoice(Invoice $invoice)
    {
        // Don't post draft or cancelled invoices
        if (in_array($invoice->status->value, ['draft', 'cancelled'])) {
            $this->reverseJournalFor($invoice);
            return;
        }

        DB::transaction(function () use ($invoice) {
            $this->reverseJournalFor($invoice);
            $accounts = $this->getStandardAccounts($invoice->organization);

            $entry = JournalEntry::create([
                'organization_id' => $invoice->organization_id,
                'date' => $invoice->invoice_date ?? now(),
                'reference_number' => $invoice->invoice_number,
                'description' => "Invoice #" . $invoice->invoice_number,
                'journalable_type' => Invoice::class,
                'journalable_id' => $invoice->id,
            ]);

            // Debit A/R (or Cash if cash invoice) for grand total
            // Assuming default is A/R. We check if invoice_basis is cash.
            // Wait, does Invoice model have invoice_basis? The user requested "cash invoice and credit based invoice".
            // We'll check if there's a cash property. If not, default to A/R.
            $debitAccount = (isset($invoice->invoice_basis) && $invoice->invoice_basis === 'cash') ? $accounts['cash'] : $accounts['ar'];

            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $debitAccount->id,
                'debit' => $invoice->grand_total,
                'credit' => 0,
                'description' => 'Amount due from customer'
            ]);

            // Credit Sales/Service for subtotal
            $revenueAccount = ($invoice->invoice_type->value === 'service') ? $accounts['service'] : $accounts['sales'];
            
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $revenueAccount->id,
                'debit' => 0,
                'credit' => $invoice->subtotal,
                'description' => 'Revenue from invoice'
            ]);

            // Credit Tax Payable for tax_total
            if ($invoice->tax_total > 0) {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $accounts['tax_payable']->id,
                    'debit' => 0,
                    'credit' => $invoice->tax_total,
                    'description' => 'Output Tax'
                ]);
            }
            
            // Debit Discount (if any)
            if ($invoice->discount_amount > 0) {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $accounts['discount']->id,
                    'debit' => $invoice->discount_amount,
                    'credit' => 0,
                    'description' => 'Discount Given'
                ]);
            }
        });
    }

    /**
     * Post journal entry for a Purchase
     */
    public function postPurchase(PurchaseInvoice $purchase)
    {
        // Don't post draft purchases
        if (in_array($purchase->status->value, ['draft', 'cancelled'])) {
            $this->reverseJournalFor($purchase);
            return;
        }

        DB::transaction(function () use ($purchase) {
            $this->reverseJournalFor($purchase);
            $accounts = $this->getStandardAccounts($purchase->organization);

            $entry = JournalEntry::create([
                'organization_id' => $purchase->organization_id,
                'date' => $purchase->purchase_date ?? now(),
                'reference_number' => $purchase->bill_number ?? $purchase->purchase_number,
                'description' => "Purchase #" . $purchase->purchase_number,
                'journalable_type' => PurchaseInvoice::class,
                'journalable_id' => $purchase->id,
            ]);

            // Credit A/P for grand total
            $creditAccount = (isset($purchase->purchase_basis) && $purchase->purchase_basis === 'cash') ? $accounts['cash'] : $accounts['ap'];

            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $creditAccount->id,
                'debit' => 0,
                'credit' => $purchase->grand_total,
                'description' => 'Amount due to vendor'
            ]);

            // Debit COGS/Inventory for subtotal
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $accounts['cogs']->id,
                'debit' => $purchase->subtotal,
                'credit' => 0,
                'description' => 'Purchase Cost'
            ]);

            // Debit Tax Receivable for tax_total
            if ($purchase->tax_total > 0) {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $accounts['tax_receivable']->id,
                    'debit' => $purchase->tax_total,
                    'credit' => 0,
                    'description' => 'Input Tax'
                ]);
            }
            
            // Credit Discount (if any)
            if ($purchase->discount_amount > 0) {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $accounts['discount']->id,
                    'debit' => 0,
                    'credit' => $purchase->discount_amount,
                    'description' => 'Discount Received'
                ]);
            }
        });
    }

    /**
     * Post journal entry for a Receipt
     */
    public function postReceipt(Receipt $receipt)
    {
        DB::transaction(function () use ($receipt) {
            $this->reverseJournalFor($receipt);
            $accounts = $this->getStandardAccounts($receipt->organization);

            $entry = JournalEntry::create([
                'organization_id' => $receipt->organization_id,
                'date' => $receipt->receipt_date ?? now(),
                'reference_number' => $receipt->receipt_number,
                'description' => "Receipt #" . $receipt->receipt_number,
                'journalable_type' => Receipt::class,
                'journalable_id' => $receipt->id,
            ]);

            // Debit Bank/Cash
            $debitAccount = (strtolower($receipt->payment_mode?->value ?? '') === 'cash') ? $accounts['cash'] : $accounts['bank'];

            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $debitAccount->id,
                'debit' => $receipt->amount,
                'credit' => 0,
                'description' => 'Payment received'
            ]);

            // Credit A/R
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $accounts['ar']->id,
                'debit' => 0,
                'credit' => $receipt->amount,
                'description' => 'Payment applied to A/R'
            ]);
        });
    }

    /**
     * Post journal entry for a Payment Voucher
     */
    public function postPaymentVoucher(PaymentVoucher $voucher)
    {
        DB::transaction(function () use ($voucher) {
            $this->reverseJournalFor($voucher);
            $accounts = $this->getStandardAccounts($voucher->organization);

            $entry = JournalEntry::create([
                'organization_id' => $voucher->organization_id,
                'date' => $voucher->voucher_date ?? now(),
                'reference_number' => $voucher->voucher_number,
                'description' => "Payment Voucher #" . $voucher->voucher_number,
                'journalable_type' => PaymentVoucher::class,
                'journalable_id' => $voucher->id,
            ]);

            // Credit Bank/Cash
            $creditAccount = (strtolower($voucher->payment_mode?->value ?? '') === 'cash') ? $accounts['cash'] : $accounts['bank'];

            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $creditAccount->id,
                'debit' => 0,
                'credit' => $voucher->amount,
                'description' => 'Payment made'
            ]);

            // Debit A/P
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $accounts['ap']->id,
                'debit' => $voucher->amount,
                'credit' => 0,
                'description' => 'Payment applied to A/P'
            ]);
        });
    }

    /**
     * Reverse any existing journal entries for a model (used on updates or deletes)
     */
    public function reverseJournalFor($model)
    {
        $entries = JournalEntry::where('journalable_type', get_class($model))
            ->where('journalable_id', $model->id)
            ->get();
            
        foreach ($entries as $entry) {
            $entry->lines()->delete();
            $entry->delete();
        }
    }
}
