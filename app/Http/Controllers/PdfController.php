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

        // Check for template_id in query parameters
        $template = null;
        if ($request->has('template_id')) {
            $template = $invoice->organization->invoiceTemplates()->find($request->query('template_id'));
        }

        // Load the default template for the organization if not provided or found
        if (!$template) {
            $template = $invoice->organization->invoiceTemplates()->where('is_default', true)->first();
        }
        
        if (!$template) {
            $template = new \App\Models\InvoiceTemplate([
                'color_primary' => '#4F46E5',
                'show_fields' => \App\Models\InvoiceTemplate::defaultShowFields(),
            ]);
        }

        // Override show_fields if provided in query parameters
        if ($request->has('show_fields')) {
            $template->show_fields = array_merge($template->show_fields ?? [], $request->query('show_fields'));
        }

        // Pass data to view
        $viewName = $template->slug ? "pdf.templates.{$template->slug}" : 'pdf.templates.standard';
        
        if (!view()->exists($viewName)) {
            $viewName = 'pdf.templates.standard';
        }
        
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
