<x-layouts.public title="Help Center">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20" 
         x-data="{ 
            searchQuery: '',
            activeCategory: 'getting-started', 
            activeAccordion: null,
            
            checkMatch(title, content) {
                if (this.searchQuery.trim() === '') return true;
                const query = this.searchQuery.toLowerCase();
                return title.toLowerCase().includes(query) || content.toLowerCase().includes(query);
            }
         }">
        
        <!-- Hero Section with Search -->
        <div class="text-center mb-16 relative">
            <div class="absolute inset-0 flex justify-center items-center pointer-events-none opacity-20">
                <div class="w-[500px] h-[500px] bg-primary rounded-full filter blur-[120px] mix-blend-screen"></div>
            </div>
            
            <span class="inline-block py-1 px-3 rounded-full bg-primary/10 border border-primary/20 text-primary text-xs font-bold tracking-widest uppercase mb-6 shadow-[0_0_15px_rgba(245,158,11,0.2)]">Support</span>
            
            <h1 class="relative text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-white to-white/60 mb-6 tracking-tight leading-tight">
                How can we help you today?
            </h1>
            <p class="relative text-lg text-text-muted max-w-2xl mx-auto mb-10">
                Search our knowledge base or browse categories below to find exactly what you're looking for.
            </p>

            <!-- Search Input -->
            <div class="max-w-2xl mx-auto relative group z-20">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-text-muted group-focus-within:text-primary transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" 
                       x-model="searchQuery"
                       placeholder="Search for articles, guides, and FAQs..." 
                       class="block w-full pl-12 pr-4 py-4 bg-surface-lighter/50 border border-white/10 rounded-2xl text-white placeholder-text-muted/50 focus:ring-2 focus:ring-primary focus:border-transparent transition-all shadow-xl backdrop-blur-md text-lg">
                <button x-show="searchQuery.length > 0" @click="searchQuery = ''" class="absolute inset-y-0 right-0 pr-4 flex items-center text-text-muted hover:text-white transition-colors" x-cloak>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 relative z-10">
            <!-- Sidebar Categories (Hidden when searching) -->
            <div class="lg:w-1/3 flex-shrink-0" x-show="searchQuery === ''" x-transition.opacity>
                <div class="p-2 rounded-3xl border border-white/5 bg-surface-lighter/20 backdrop-blur-sm space-y-1 sticky top-24">
                    
                    <button @click="activeCategory = 'getting-started'; activeAccordion = null" 
                            class="w-full flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 text-left relative overflow-hidden group"
                            :class="activeCategory === 'getting-started' ? 'bg-white/5 shadow-lg' : 'hover:bg-white/[0.02]'">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-transparent opacity-0 transition-opacity duration-300" :class="{ 'opacity-100': activeCategory === 'getting-started' }"></div>
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary rounded-r-full transform origin-left transition-transform duration-300" :class="activeCategory === 'getting-started' ? 'scale-x-100' : 'scale-x-0'"></div>
                        
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 relative z-10"
                             :class="activeCategory === 'getting-started' ? 'bg-primary text-surface shadow-[0_0_20px_rgba(245,158,11,0.3)]' : 'bg-surface-lighter text-primary group-hover:bg-surface'">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div class="relative z-10">
                            <h3 class="font-bold text-lg transition-colors" :class="activeCategory === 'getting-started' ? 'text-white' : 'text-text-primary group-hover:text-white'">Getting Started</h3>
                            <p class="text-xs text-text-muted mt-0.5">Setup & Configuration</p>
                        </div>
                    </button>

                    <button @click="activeCategory = 'invoicing'; activeAccordion = null" 
                            class="w-full flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 text-left relative overflow-hidden group"
                            :class="activeCategory === 'invoicing' ? 'bg-white/5 shadow-lg' : 'hover:bg-white/[0.02]'">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-transparent opacity-0 transition-opacity duration-300" :class="{ 'opacity-100': activeCategory === 'invoicing' }"></div>
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary rounded-r-full transform origin-left transition-transform duration-300" :class="activeCategory === 'invoicing' ? 'scale-x-100' : 'scale-x-0'"></div>
                        
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 relative z-10"
                             :class="activeCategory === 'invoicing' ? 'bg-primary text-surface shadow-[0_0_20px_rgba(245,158,11,0.3)]' : 'bg-surface-lighter text-primary group-hover:bg-surface'">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <div class="relative z-10">
                            <h3 class="font-bold text-lg transition-colors" :class="activeCategory === 'invoicing' ? 'text-white' : 'text-text-primary group-hover:text-white'">Invoicing & Billing</h3>
                            <p class="text-xs text-text-muted mt-0.5">Creating & managing invoices</p>
                        </div>
                    </button>

                    <button @click="activeCategory = 'inventory'; activeAccordion = null" 
                            class="w-full flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 text-left relative overflow-hidden group"
                            :class="activeCategory === 'inventory' ? 'bg-white/5 shadow-lg' : 'hover:bg-white/[0.02]'">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-transparent opacity-0 transition-opacity duration-300" :class="{ 'opacity-100': activeCategory === 'inventory' }"></div>
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary rounded-r-full transform origin-left transition-transform duration-300" :class="activeCategory === 'inventory' ? 'scale-x-100' : 'scale-x-0'"></div>
                        
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 relative z-10"
                             :class="activeCategory === 'inventory' ? 'bg-primary text-surface shadow-[0_0_20px_rgba(245,158,11,0.3)]' : 'bg-surface-lighter text-primary group-hover:bg-surface'">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </div>
                        <div class="relative z-10">
                            <h3 class="font-bold text-lg transition-colors" :class="activeCategory === 'inventory' ? 'text-white' : 'text-text-primary group-hover:text-white'">Inventory Management</h3>
                            <p class="text-xs text-text-muted mt-0.5">Stock & purchase tracking</p>
                        </div>
                    </button>

                    <button @click="activeCategory = 'comprehensive-guide'; activeAccordion = null" 
                            class="w-full flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 text-left relative overflow-hidden group"
                            :class="activeCategory === 'comprehensive-guide' ? 'bg-white/5 shadow-lg' : 'hover:bg-white/[0.02]'">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-transparent opacity-0 transition-opacity duration-300" :class="{ 'opacity-100': activeCategory === 'comprehensive-guide' }"></div>
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary rounded-r-full transform origin-left transition-transform duration-300" :class="activeCategory === 'comprehensive-guide' ? 'scale-x-100' : 'scale-x-0'"></div>
                        
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 relative z-10"
                             :class="activeCategory === 'comprehensive-guide' ? 'bg-primary text-surface shadow-[0_0_20px_rgba(245,158,11,0.3)]' : 'bg-surface-lighter text-primary group-hover:bg-surface'">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <div class="relative z-10">
                            <h3 class="font-bold text-lg transition-colors" :class="activeCategory === 'comprehensive-guide' ? 'text-white' : 'text-text-primary group-hover:text-white'">Full User Guide</h3>
                            <p class="text-xs text-text-muted mt-0.5">Complete onboarding & features</p>
                        </div>
                    </button>

                    <div class="pt-6 pb-4 text-center">
                        <p class="text-xs text-text-muted mb-4 uppercase tracking-wider font-semibold">Still need help?</p>
                        <a href="{{ route('contact-support') }}" class="inline-flex items-center justify-center gap-2 text-primary hover:text-primary-light transition-colors text-sm font-bold">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="w-full" :class="searchQuery === '' ? 'lg:w-2/3' : 'lg:w-full max-w-4xl mx-auto'">
                
                <!-- Search Results Header -->
                <div x-show="searchQuery !== ''" x-cloak class="mb-8 flex items-center justify-between border-b border-white/10 pb-4">
                    <h2 class="text-xl font-bold text-white">
                        Search Results for "<span class="text-primary" x-text="searchQuery"></span>"
                    </h2>
                    <button @click="searchQuery = ''" class="text-sm text-text-muted hover:text-white transition-colors">Clear Search</button>
                </div>

                <div class="space-y-4">
                    
                    <!-- Getting Started Content -->
                    <div x-show="(searchQuery === '' && activeCategory === 'getting-started') || (searchQuery !== '')" 
                         class="space-y-4"
                         x-transition.opacity>
                        
                        <div x-show="searchQuery === ''" class="mb-6">
                            <h2 class="text-3xl font-extrabold text-white mb-2">Getting Started</h2>
                            <p class="text-text-muted text-lg">Master the basics and get your organization up and running.</p>
                        </div>
                        <div x-show="searchQuery !== '' && (checkMatch('How do I set up my organization?', 'Navigate to Settings') || checkMatch('How do I invite team members?', 'User Management'))" class="text-xs font-bold text-primary uppercase tracking-wider mb-2 mt-8">Getting Started</div>

                        <!-- FAQ 1 -->
                        <div class="glass-card rounded-2xl border border-white/10 overflow-hidden transition-all duration-300"
                             :class="{ 'border-primary/50 shadow-[0_0_30px_rgba(245,158,11,0.15)]': activeAccordion === 1 }"
                             x-show="checkMatch('How do I set up my organization?', 'Navigate to Settings from the sidebar, enter your business details')">
                            <button @click="activeAccordion = activeAccordion === 1 ? null : 1" class="w-full text-left p-6 flex justify-between items-center focus:outline-none hover:bg-white/[0.02] transition-colors group">
                                <h4 class="text-lg font-bold text-white group-hover:text-primary transition-colors">How do I set up my organization?</h4>
                                <div class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center transform transition-all duration-300" 
                                     :class="activeAccordion === 1 ? 'rotate-180 bg-primary border-primary text-surface' : 'bg-surface-lighter text-text-muted group-hover:border-white/30'">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </button>
                            <div class="grid transition-all duration-300 ease-in-out" :class="activeAccordion === 1 ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'">
                                <div class="overflow-hidden">
                                    <div class="p-6 pt-0 text-text-muted leading-relaxed border-t border-white/5 mt-2">
                                        Navigate to <strong class="text-white">Settings</strong> from the sidebar, enter your business details, and upload your logo. This information will automatically appear on all your invoices and receipts for a professional look.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="glass-card rounded-2xl border border-white/10 overflow-hidden transition-all duration-300"
                             :class="{ 'border-primary/50 shadow-[0_0_30px_rgba(245,158,11,0.15)]': activeAccordion === 2 }"
                             x-show="checkMatch('How do I invite team members?', 'Head over to the Settings > User Management area')">
                            <button @click="activeAccordion = activeAccordion === 2 ? null : 2" class="w-full text-left p-6 flex justify-between items-center focus:outline-none hover:bg-white/[0.02] transition-colors group">
                                <h4 class="text-lg font-bold text-white group-hover:text-primary transition-colors">How do I invite team members?</h4>
                                <div class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center transform transition-all duration-300" 
                                     :class="activeAccordion === 2 ? 'rotate-180 bg-primary border-primary text-surface' : 'bg-surface-lighter text-text-muted group-hover:border-white/30'">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </button>
                            <div class="grid transition-all duration-300 ease-in-out" :class="activeAccordion === 2 ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'">
                                <div class="overflow-hidden">
                                    <div class="p-6 pt-0 text-text-muted leading-relaxed border-t border-white/5 mt-2">
                                        Head over to the <strong class="text-white">Settings > User Management</strong> area to invite team members. You can assign them specific roles such as <span class="bg-primary/20 text-primary px-2 py-0.5 rounded text-xs">Org Admin</span>, <span class="bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded text-xs">Accountant</span>, or <span class="bg-gray-500/20 text-gray-400 px-2 py-0.5 rounded text-xs">Staff</span> to control their access levels securely.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoicing Content -->
                    <div x-show="(searchQuery === '' && activeCategory === 'invoicing') || (searchQuery !== '')" 
                         class="space-y-4"
                         x-transition.opacity>
                        
                        <div x-show="searchQuery === ''" class="mb-6">
                            <h2 class="text-3xl font-extrabold text-white mb-2">Invoicing & Billing</h2>
                            <p class="text-text-muted text-lg">Learn how to create stunning invoices and track your payments.</p>
                        </div>
                        <div x-show="searchQuery !== '' && (checkMatch('How do I create a new invoice?', 'Go to the Invoices tab') || checkMatch('Can I add a discount to an invoice?', 'discount') || checkMatch('What happens when a customer pays?', 'Receipt'))" class="text-xs font-bold text-primary uppercase tracking-wider mb-2 mt-8">Invoicing & Billing</div>

                        <!-- FAQ 3 -->
                        <div class="glass-card rounded-2xl border border-white/10 overflow-hidden transition-all duration-300"
                             :class="{ 'border-primary/50 shadow-[0_0_30px_rgba(245,158,11,0.15)]': activeAccordion === 3 }"
                             x-show="checkMatch('How do I create a new invoice?', 'Go to the Invoices tab and click Create Invoice')">
                            <button @click="activeAccordion = activeAccordion === 3 ? null : 3" class="w-full text-left p-6 flex justify-between items-center focus:outline-none hover:bg-white/[0.02] transition-colors group">
                                <h4 class="text-lg font-bold text-white group-hover:text-primary transition-colors">How do I create a new invoice?</h4>
                                <div class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center transform transition-all duration-300" 
                                     :class="activeAccordion === 3 ? 'rotate-180 bg-primary border-primary text-surface' : 'bg-surface-lighter text-text-muted group-hover:border-white/30'">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </button>
                            <div class="grid transition-all duration-300 ease-in-out" :class="activeAccordion === 3 ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'">
                                <div class="overflow-hidden">
                                    <div class="p-6 pt-0 text-text-muted leading-relaxed border-t border-white/5 mt-2">
                                        Go to the <strong class="text-white">Invoices</strong> tab and click <strong class="text-white">Create Invoice</strong>. Select an existing customer or add a new one on the fly, add your products or services, and hit save to generate a beautiful PDF.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="glass-card rounded-2xl border border-white/10 overflow-hidden transition-all duration-300"
                             :class="{ 'border-primary/50 shadow-[0_0_30px_rgba(245,158,11,0.15)]': activeAccordion === 4 }"
                             x-show="checkMatch('Can I add a discount to an invoice?', 'Yes! You can add a discount at the individual product level')">
                            <button @click="activeAccordion = activeAccordion === 4 ? null : 4" class="w-full text-left p-6 flex justify-between items-center focus:outline-none hover:bg-white/[0.02] transition-colors group">
                                <h4 class="text-lg font-bold text-white group-hover:text-primary transition-colors">Can I add a discount to an invoice?</h4>
                                <div class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center transform transition-all duration-300" 
                                     :class="activeAccordion === 4 ? 'rotate-180 bg-primary border-primary text-surface' : 'bg-surface-lighter text-text-muted group-hover:border-white/30'">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </button>
                            <div class="grid transition-all duration-300 ease-in-out" :class="activeAccordion === 4 ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'">
                                <div class="overflow-hidden">
                                    <div class="p-6 pt-0 text-text-muted leading-relaxed border-t border-white/5 mt-2">
                                        Absolutely! You can add a discount at the <strong class="text-white">individual product level</strong> when adding items to your invoice, or you can apply a <strong class="text-white">global discount</strong> on the grand total at the bottom of the invoice form.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 5 -->
                        <div class="glass-card rounded-2xl border border-white/10 overflow-hidden transition-all duration-300"
                             :class="{ 'border-primary/50 shadow-[0_0_30px_rgba(245,158,11,0.15)]': activeAccordion === 5 }"
                             x-show="checkMatch('What happens when a customer pays?', 'Go to the Finance section and create a new Receipt')">
                            <button @click="activeAccordion = activeAccordion === 5 ? null : 5" class="w-full text-left p-6 flex justify-between items-center focus:outline-none hover:bg-white/[0.02] transition-colors group">
                                <h4 class="text-lg font-bold text-white group-hover:text-primary transition-colors">What happens when a customer pays?</h4>
                                <div class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center transform transition-all duration-300" 
                                     :class="activeAccordion === 5 ? 'rotate-180 bg-primary border-primary text-surface' : 'bg-surface-lighter text-text-muted group-hover:border-white/30'">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </button>
                            <div class="grid transition-all duration-300 ease-in-out" :class="activeAccordion === 5 ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'">
                                <div class="overflow-hidden">
                                    <div class="p-6 pt-0 text-text-muted leading-relaxed border-t border-white/5 mt-2">
                                        Go to the <strong class="text-white">Finance</strong> section and create a new <strong class="text-white">Receipt</strong>. Enter the amount received and allocate it against the specific unpaid invoice. The invoice status will automatically update to <span class="text-yellow-400">Partial</span> or <span class="text-green-400">Paid</span> based on the allocated amount.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Content -->
                    <div x-show="(searchQuery === '' && activeCategory === 'inventory') || (searchQuery !== '')" 
                         class="space-y-4"
                         x-transition.opacity>
                        
                        <div x-show="searchQuery === ''" class="mb-6">
                            <h2 class="text-3xl font-extrabold text-white mb-2">Inventory Management</h2>
                            <p class="text-text-muted text-lg">Keep your stock in check effortlessly.</p>
                        </div>
                        <div x-show="searchQuery !== '' && (checkMatch('How does inventory tracking work?', 'Purchase Invoice will add stock') || checkMatch('How do I record vendor payments?', 'Payment Voucher'))" class="text-xs font-bold text-primary uppercase tracking-wider mb-2 mt-8">Inventory Management</div>

                        <!-- FAQ 6 -->
                        <div class="glass-card rounded-2xl border border-white/10 overflow-hidden transition-all duration-300"
                             :class="{ 'border-primary/50 shadow-[0_0_30px_rgba(245,158,11,0.15)]': activeAccordion === 6 }"
                             x-show="checkMatch('How does inventory tracking work?', 'Your inventory is automatically updated in real-time')">
                            <button @click="activeAccordion = activeAccordion === 6 ? null : 6" class="w-full text-left p-6 flex justify-between items-center focus:outline-none hover:bg-white/[0.02] transition-colors group">
                                <h4 class="text-lg font-bold text-white group-hover:text-primary transition-colors">How does inventory tracking work?</h4>
                                <div class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center transform transition-all duration-300" 
                                     :class="activeAccordion === 6 ? 'rotate-180 bg-primary border-primary text-surface' : 'bg-surface-lighter text-text-muted group-hover:border-white/30'">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </button>
                            <div class="grid transition-all duration-300 ease-in-out" :class="activeAccordion === 6 ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'">
                                <div class="overflow-hidden">
                                    <div class="p-6 pt-0 text-text-muted leading-relaxed border-t border-white/5 mt-2">
                                        Your inventory is automatically updated in real-time. Creating a <strong class="text-white">Purchase Invoice</strong> will add stock to your inventory, while creating a <strong class="text-white">Sales Invoice</strong> will seamlessly deduct stock.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 7 -->
                        <div class="glass-card rounded-2xl border border-white/10 overflow-hidden transition-all duration-300"
                             :class="{ 'border-primary/50 shadow-[0_0_30px_rgba(245,158,11,0.15)]': activeAccordion === 7 }"
                             x-show="checkMatch('How do I record vendor payments?', 'create a Payment Voucher')">
                            <button @click="activeAccordion = activeAccordion === 7 ? null : 7" class="w-full text-left p-6 flex justify-between items-center focus:outline-none hover:bg-white/[0.02] transition-colors group">
                                <h4 class="text-lg font-bold text-white group-hover:text-primary transition-colors">How do I record vendor payments?</h4>
                                <div class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center transform transition-all duration-300" 
                                     :class="activeAccordion === 7 ? 'rotate-180 bg-primary border-primary text-surface' : 'bg-surface-lighter text-text-muted group-hover:border-white/30'">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </button>
                            <div class="grid transition-all duration-300 ease-in-out" :class="activeAccordion === 7 ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'">
                                <div class="overflow-hidden">
                                    <div class="p-6 pt-0 text-text-muted leading-relaxed border-t border-white/5 mt-2">
                                        To record a payment made to a supplier, go to the <strong class="text-white">Finance</strong> section and create a <strong class="text-white">Payment Voucher</strong>. You can then allocate the paid amount against any outstanding Purchase Invoices to mark them as paid.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comprehensive Guide Content -->
                    <div x-show="(searchQuery === '' && activeCategory === 'comprehensive-guide') || (searchQuery !== '')" 
                         class="space-y-4"
                         x-transition.opacity>
                        
                        <div x-show="searchQuery === ''" class="mb-6">
                            <h2 class="text-3xl font-extrabold text-white mb-2">Comprehensive User Guide</h2>
                            <p class="text-text-muted text-lg">A complete walkthrough from onboarding to advanced features.</p>
                        </div>
                        <div x-show="searchQuery !== '' && (checkMatch('guide', 'guide') || checkMatch('onboarding', 'onboarding') || checkMatch('organization', 'organization'))" class="text-xs font-bold text-primary uppercase tracking-wider mb-2 mt-8">Comprehensive Guide</div>

                        <div class="glass-card rounded-2xl border border-white/10 overflow-hidden" x-show="checkMatch('Comprehensive Guide', 'organization, contacts, products, inventory, sales, purchases, receipts, vouchers, ledger, reports')">
                            <div class="p-8 prose prose-invert prose-primary max-w-none text-text-muted">
                                <h3 class="text-white">1. Organization Management</h3>
                                <p>Manage multiple businesses (organizations) from a single account. Switch between them using your avatar menu. Customize your organization's GST registered status, Composition Scheme status, and default currency in Settings.</p>
                                
                                <h3 class="text-white mt-6">2. Contacts (Customers & Vendors)</h3>
                                <ul>
                                    <li><strong>Customers:</strong> Used primarily for Sales Invoices and Receipts.</li>
                                    <li><strong>Vendors:</strong> Used for Purchase Bills and Payment Vouchers.</li>
                                </ul>

                                <h3 class="text-white mt-6">3. Products & Inventory</h3>
                                <p>For Products, Cratory tracks inventory automatically. Creating a Purchase Bill (marked "Received") increases stock, and sending a Sales Invoice decreases it.</p>

                                <h3 class="text-white mt-6">4. Sales Invoices</h3>
                                <ul>
                                    <li><strong>Credit Invoices:</strong> Payment expected later. Can be tracked for outstanding balances.</li>
                                    <li><strong>Cash Invoices:</strong> Immediate payments. Automatically creates a "Receipt" to mark the transaction as fully paid instantly.</li>
                                </ul>

                                <h3 class="text-white mt-6">5. Purchase Bills</h3>
                                <p>Log your incoming goods and expenses. Drafts are work-in-progress, while "Received" finalizes the bill and triggers automated inventory addition.</p>

                                <h3 class="text-white mt-6">6. Receipts & Payment Vouchers</h3>
                                <p><strong>Receipts:</strong> Log incoming payments from Customers and allocate them across open Sales Invoices. <br>
                                <strong>Payment Vouchers:</strong> Log outgoing payments to Vendors and allocate them across open Purchase Bills.</p>

                                <h3 class="text-white mt-6">7. The Ledger & Accounting</h3>
                                <p>Cratory features a fully automated Double-Entry Accounting system. Whenever a Sale, Purchase, Receipt, or Voucher is finalized, Cratory automatically generates the corresponding Journal Entries (Debits and Credits) to the correct Accounts.</p>

                                <h3 class="text-white mt-6">8. Reports & Analytics</h3>
                                <p>Powerful financial reports (Sales, Purchases, Profit & Loss, Trial Balance, Balance Sheet) that can be viewed on-screen or exported as PDF / CSV.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Empty State -->
                    <div x-show="searchQuery !== '' && !checkMatch('organization', 'organization') && !checkMatch('invoice', 'invoice') && !checkMatch('inventory', 'inventory') && !checkMatch('vendor', 'vendor') && !checkMatch('discount', 'discount') && !checkMatch('pays', 'pays') && !checkMatch('guide', 'guide')" class="text-center py-12" x-cloak>
                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">No results found</h3>
                        <p class="text-text-muted">We couldn't find any articles matching "<span x-text="searchQuery" class="text-white"></span>".</p>
                        <button @click="searchQuery = ''" class="mt-6 text-primary hover:text-primary-light transition-colors font-medium">Clear search and browse categories</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
