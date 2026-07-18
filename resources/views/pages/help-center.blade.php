<x-layouts.public title="Help Center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20" x-data="{ activeAccordion: null }">
        
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6">How can we help you?</h1>
            <p class="text-lg text-text-muted max-w-2xl mx-auto">
                Find simple answers to the most common questions about using Cratory for your business.
            </p>
        </div>

        <div class="space-y-12">
            
            <!-- Category 1: Getting Started -->
            <div>
                <h2 class="text-2xl font-bold text-white mb-6 border-b border-white/10 pb-4">Getting Started</h2>
                <div class="space-y-4">
                    <!-- FAQ 1 -->
                    <div class="glass-card rounded-xl border border-white/10 overflow-hidden">
                        <button @click="activeAccordion = activeAccordion === 1 ? null : 1" class="w-full text-left p-6 flex justify-between items-center focus:outline-none hover:bg-white/[0.02] transition-colors">
                            <h4 class="text-lg font-bold text-white">How do I set up my business profile?</h4>
                            <svg class="w-5 h-5 text-text-muted transform transition-transform" :class="{ 'rotate-180': activeAccordion === 1 }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="activeAccordion === 1" x-collapse>
                            <div class="p-6 pt-0 text-text-muted leading-relaxed border-t border-white/5">
                                Go to <strong>Settings</strong> from the left menu. Here you can enter your business name, address, tax details, and upload your company logo. This information will appear on all your invoices.
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 2 -->
                    <div class="glass-card rounded-xl border border-white/10 overflow-hidden">
                        <button @click="activeAccordion = activeAccordion === 2 ? null : 2" class="w-full text-left p-6 flex justify-between items-center focus:outline-none hover:bg-white/[0.02] transition-colors">
                            <h4 class="text-lg font-bold text-white">How do I invite my team?</h4>
                            <svg class="w-5 h-5 text-text-muted transform transition-transform" :class="{ 'rotate-180': activeAccordion === 2 }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="activeAccordion === 2" x-collapse>
                            <div class="p-6 pt-0 text-text-muted leading-relaxed border-t border-white/5">
                                Go to <strong>Settings > User Management</strong>. Click "Add User", enter their details, and assign them a role (like Admin, Accountant, or Staff). They can then log in and start working with you.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category 2: Invoicing & Billing -->
            <div>
                <h2 class="text-2xl font-bold text-white mb-6 border-b border-white/10 pb-4">Invoicing & Payments</h2>
                <div class="space-y-4">
                    <!-- FAQ 3 -->
                    <div class="glass-card rounded-xl border border-white/10 overflow-hidden">
                        <button @click="activeAccordion = activeAccordion === 3 ? null : 3" class="w-full text-left p-6 flex justify-between items-center focus:outline-none hover:bg-white/[0.02] transition-colors">
                            <h4 class="text-lg font-bold text-white">How do I create and send an invoice?</h4>
                            <svg class="w-5 h-5 text-text-muted transform transition-transform" :class="{ 'rotate-180': activeAccordion === 3 }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="activeAccordion === 3" x-collapse>
                            <div class="p-6 pt-0 text-text-muted leading-relaxed border-t border-white/5">
                                Click on <strong>Invoices</strong> from the left menu and select "New Invoice". Choose a customer, add your items or services, and click save. You can then download it as a PDF or send it directly.
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 4 -->
                    <div class="glass-card rounded-xl border border-white/10 overflow-hidden">
                        <button @click="activeAccordion = activeAccordion === 4 ? null : 4" class="w-full text-left p-6 flex justify-between items-center focus:outline-none hover:bg-white/[0.02] transition-colors">
                            <h4 class="text-lg font-bold text-white">How do I mark an invoice as paid?</h4>
                            <svg class="w-5 h-5 text-text-muted transform transition-transform" :class="{ 'rotate-180': activeAccordion === 4 }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="activeAccordion === 4" x-collapse>
                            <div class="p-6 pt-0 text-text-muted leading-relaxed border-t border-white/5">
                                When a customer pays you, go to the <strong>Receipts</strong> tab under Finance. Click "New Receipt", enter the payment amount, and allocate it to their open invoice. The invoice will automatically update to "Paid".
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 5 -->
                    <div class="glass-card rounded-xl border border-white/10 overflow-hidden">
                        <button @click="activeAccordion = activeAccordion === 5 ? null : 5" class="w-full text-left p-6 flex justify-between items-center focus:outline-none hover:bg-white/[0.02] transition-colors">
                            <h4 class="text-lg font-bold text-white">Can I customize my invoice design?</h4>
                            <svg class="w-5 h-5 text-text-muted transform transition-transform" :class="{ 'rotate-180': activeAccordion === 5 }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="activeAccordion === 5" x-collapse>
                            <div class="p-6 pt-0 text-text-muted leading-relaxed border-t border-white/5">
                                Yes! Go to <strong>Settings > Invoice Templates</strong>. You can choose from different themes, add a custom watermark, and upload a digital signature to make your invoices look extremely professional.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category 3: Inventory & GST -->
            <div>
                <h2 class="text-2xl font-bold text-white mb-6 border-b border-white/10 pb-4">Inventory & GST E-Invoicing</h2>
                <div class="space-y-4">
                    <!-- FAQ 6 -->
                    <div class="glass-card rounded-xl border border-white/10 overflow-hidden">
                        <button @click="activeAccordion = activeAccordion === 6 ? null : 6" class="w-full text-left p-6 flex justify-between items-center focus:outline-none hover:bg-white/[0.02] transition-colors">
                            <h4 class="text-lg font-bold text-white">Do I need to manually update stock levels?</h4>
                            <svg class="w-5 h-5 text-text-muted transform transition-transform" :class="{ 'rotate-180': activeAccordion === 6 }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="activeAccordion === 6" x-collapse>
                            <div class="p-6 pt-0 text-text-muted leading-relaxed border-t border-white/5">
                                No, Cratory handles inventory automatically. When you finalize a Sales Invoice, stock is deducted from your warehouse. When you receive a Purchase Bill from a supplier, stock is added back.
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 7 -->
                    <div class="glass-card rounded-xl border border-white/10 overflow-hidden">
                        <button @click="activeAccordion = activeAccordion === 7 ? null : 7" class="w-full text-left p-6 flex justify-between items-center focus:outline-none hover:bg-white/[0.02] transition-colors">
                            <h4 class="text-lg font-bold text-white">How do I generate an E-Way Bill or IRN?</h4>
                            <svg class="w-5 h-5 text-text-muted transform transition-transform" :class="{ 'rotate-180': activeAccordion === 7 }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="activeAccordion === 7" x-collapse>
                            <div class="p-6 pt-0 text-text-muted leading-relaxed border-t border-white/5">
                                Cratory supports direct integration with the GST Portal. Simply open any finalized invoice and click "Generate IRN" or "Generate E-Way Bill". The system will automatically build the required JSON data, communicate with the government portal, and attach the signed QR code to your invoice.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Support Callout -->
            <div class="mt-16 text-center bg-surface-lighter/50 rounded-3xl p-10 border border-white/5">
                <h3 class="text-2xl font-bold text-white mb-4">Still need help?</h3>
                <p class="text-text-muted mb-6">Our support team is always ready to assist you with any questions.</p>
                <a href="{{ route('contact-support') }}" class="btn bg-primary hover:bg-primary-light text-surface px-8 py-3 rounded-xl font-bold shadow-[0_0_20px_rgba(245,158,11,0.3)] transition-all">
                    Contact Support
                </a>
            </div>

        </div>
    </div>
</x-layouts.public>
