<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function downloadInvoice(Request $request, Invoice $invoice)
    {
        // Ensure user belongs to the organization that owns this invoice
        if ($invoice->organization_id !== auth()->user()->current_organization_id) {
            abort(403);
        }

        // Eager load relations
        $invoice->load(['organization', 'contact', 'items.product', 'items.taxRate']);

        // Load the default template for the organization
        $template = $invoice->organization->invoiceTemplates()->where('is_default', true)->first();
        
        if (!$template) {
            $template = new \App\Models\InvoiceTemplate([
                'color_primary' => '#4F46E5',
                'show_fields' => \App\Models\InvoiceTemplate::defaultShowFields(),
            ]);
        }

        // Pass data to view
        $viewName = $template->slug ? "pdf.templates.{$template->slug}" : 'pdf.templates.standard';
        
        $pdf = Pdf::loadView($viewName, [
            'invoice' => $invoice,
            'organization' => $invoice->organization,
            'contact' => $invoice->contact,
            'template' => $template,
        ]);

        $filename = ($invoice->invoice_number ?? 'draft') . '.pdf';
        
        return $pdf->download($filename);
    }
}
