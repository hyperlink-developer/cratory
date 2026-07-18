<x-layouts.public title="Changelog">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6">Changelog</h1>
            <p class="text-lg text-text-muted">Keep track of the latest updates and improvements.</p>
        </div>

        <div class="glass-card p-8 rounded-3xl border border-white/5 space-y-8">
            <div class="border-b border-white/10 pb-8 mb-8">
                <div class="flex items-center gap-4 mb-4">
                    <span class="bg-primary/20 text-primary px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">v1.1.0-beta</span>
                    <span class="text-text-muted text-sm">July 2026</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Inventory, E-Invoicing & Customization</h3>
                <ul class="space-y-2 text-text-muted list-disc list-inside">
                    <li>Added Automated Inventory Management (Warehouse stock deductions/additions)</li>
                    <li>Added Direct E-Invoicing (GST) integration with NIC JSON schema support</li>
                    <li>Introduced detailed Stock Movements matching SaleOut/PurchaseIn</li>
                    <li>Added `warehouse_id` tracking for advanced inventory location management</li>
                    <li>Added Document Numbering settings (Prefix, Next Number, Suffix) for all vouchers</li>
                    <li>Added Custom Logo, Watermark, and Signature support to Invoice Templates</li>
                    <li>Improved Dashboard KPIs, charts layout, and data syncing</li>
                    <li>Upgraded User Management and Contact Support UI</li>
                </ul>
            </div>
            
            <div class="border-b border-white/10 pb-8">
                <div class="flex items-center gap-4 mb-4">
                    <span class="bg-primary/20 text-primary px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">v1.0.0</span>
                    <span class="text-text-muted text-sm">July 2026</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Initial Release</h3>
                <ul class="space-y-2 text-text-muted list-disc list-inside">
                    <li>Added Invoices module with PDF generation</li>
                    <li>Added Purchase Invoices and Inventory tracking</li>
                    <li>Added Payment Receipts and Vouchers</li>
                    <li>Introduced SweetAlert2 integration for a better user experience</li>
                    <li>Added Dashboard KPIs and Charts</li>
                </ul>
            </div>
            
            <div class="text-center pt-4">
                <a href="{{ route('welcome') }}" class="btn bg-surface-lighter hover:bg-white/10 text-white px-6 py-3 border border-white/10 rounded-xl font-medium transition-all">Back to Home</a>
            </div>
        </div>
    </div>
</x-layouts.public>
