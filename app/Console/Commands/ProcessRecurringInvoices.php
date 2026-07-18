<?php

namespace App\Console\Commands;

use App\Models\RecurringInvoiceTemplate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessRecurringInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:process-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process and generate due recurring invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to process recurring invoices...');

        $templates = RecurringInvoiceTemplate::where('is_active', true)
            ->whereDate('next_run_date', '<=', today())
            ->get();

        $count = 0;

        foreach ($templates as $template) {
            try {
                $invoice = $template->generateInvoice();
                $template->advanceNextRunDate();
                
                $count++;
                
                // If auto_send is true, we will queue the notification in Milestone 4.
                if ($template->auto_send) {
                    // TODO: Dispatch notification job
                    Log::info("Recurring invoice generated and queued for sending: {$invoice->invoice_number}");
                } else {
                    Log::info("Recurring invoice generated as draft: {$invoice->invoice_number}");
                }
            } catch (\Exception $e) {
                Log::error("Failed to generate recurring invoice for template {$template->id}: " . $e->getMessage());
                $this->error("Failed to generate recurring invoice for template {$template->id}: " . $e->getMessage());
            }
        }

        $this->info("Completed processing recurring invoices. Generated {$count} invoices.");
    }
}
