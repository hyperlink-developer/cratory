<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use App\Traits\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringInvoiceTemplate extends Model
{
    use BelongsToOrganization, HasUuid, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'contact_id',
        'frequency',
        'next_run_date',
        'auto_send',
        'template_invoice_data',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'next_run_date' => 'date',
            'auto_send' => 'boolean',
            'is_active' => 'boolean',
            'template_invoice_data' => 'array',
        ];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Advance the next run date according to the template frequency.
     */
    public function advanceNextRunDate(): void
    {
        $current = Carbon::parse($this->next_run_date);
        
        $this->next_run_date = match ($this->frequency) {
            'weekly' => $current->addWeek(),
            'monthly' => $current->addMonth(),
            'quarterly' => $current->addMonths(3),
            'yearly' => $current->addYear(),
            default => $current->addMonth(),
        };

        $this->save();
    }

    /**
     * Generates a real invoice based on the template data.
     */
    public function generateInvoice(): Invoice
    {
        $data = $this->template_invoice_data;
        $generator = app(\App\Services\DocumentNumberGenerator::class);
        $organization = $this->organization;
        
        $invoice = Invoice::create([
            'organization_id' => $this->organization_id,
            'contact_id' => $this->contact_id,
            'invoice_basis' => $data['invoice_basis'] ?? 'cash',
            'invoice_type' => $data['invoice_type'] ?? 'sales',
            'invoice_number' => $generator->generate($organization, 'INV'),
            'invoice_date' => today(),
            'due_date' => today()->addDays($data['due_days'] ?? 15),
            'subtotal' => $data['subtotal'] ?? 0,
            'discount_total' => $data['discount_total'] ?? 0,
            'tax_total' => $data['tax_total'] ?? 0,
            'round_off' => $data['round_off'] ?? 0,
            'grand_total' => $data['grand_total'] ?? 0,
            'amount_paid' => 0,
            'balance_due' => $data['grand_total'] ?? 0,
            'status' => 'draft',
            'notes' => $data['notes'] ?? null,
            'terms_and_conditions' => $data['terms_and_conditions'] ?? null,
            'place_of_supply' => $data['place_of_supply'] ?? null,
        ]);

        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $itemData) {
                $invoice->items()->create([
                    'product_id' => $itemData['product_id'] ?? null,
                    'item_name' => $itemData['item_name'] ?? $itemData['name'] ?? null,
                    'description' => $itemData['description'] ?? null,
                    'hsn_code' => $itemData['hsn_code'] ?? $itemData['hsn_sac'] ?? null,
                    'quantity' => $itemData['quantity'],
                    'unit' => $itemData['unit'] ?? null,
                    'rate' => $itemData['rate'] ?? $itemData['unit_price'] ?? 0,
                    'discount_percent' => $itemData['discount_percent'] ?? 0,
                    'discount_amount' => $itemData['discount_amount'] ?? 0,
                    'tax_rate_id' => $itemData['tax_rate_id'] ?? null,
                    'tax_amount' => $itemData['tax_amount'] ?? 0,
                    'line_total' => $itemData['line_total'],
                ]);
            }
        }

        return $invoice;
    }
}
