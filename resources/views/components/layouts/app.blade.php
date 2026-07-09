<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Cratory — Invoice & Inventory Management for your business">

    <title>{{ $title ?? 'Dashboard' }} — Cratory</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/apexcharts" data-navigate-track></script>
</head>
<body class="antialiased">

    <!-- ═══ Desktop Sidebar ═══ -->
    <aside class="sidebar" id="desktop-sidebar">
        <!-- Logo -->
        <div class="px-6 mb-8">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('logo.png') }}" alt="Cratory" class="w-9 h-9 rounded-xl object-contain">
                <span class="text-lg font-bold text-text-primary tracking-tight">Cratory</span>
            </a>
        </div>

        <!-- Org Switcher -->
        @auth
        <div class="px-4 mb-6">
            @livewire('org-switcher')
        </div>
        @endauth

        <!-- Nav Links -->
        <nav class="flex-1 space-y-0.5">
            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('invoices.index') }}"
               class="sidebar-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                Invoices
            </a>

            @if(auth()->user()?->currentOrganization?->showsPurchases())
            <a href="{{ route('purchases.index') }}"
               class="sidebar-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                Purchases
            </a>
            @endif

            <a href="{{ route('contacts.index') }}"
               class="sidebar-link {{ request()->routeIs('contacts.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                Contacts
            </a>

            <a href="{{ route('inventory.index') }}"
               class="sidebar-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                Inventory
            </a>

            <div class="px-6 pt-4 pb-2">
                <p class="text-[0.625rem] uppercase tracking-widest text-text-muted font-semibold">Payments</p>
            </div>

            <a href="{{ route('receipts.index') }}"
               class="sidebar-link {{ request()->routeIs('receipts.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                </svg>
                Receipts
            </a>

            @if(auth()->user()?->currentOrganization?->showsPurchases())
            <a href="{{ route('vouchers.index') }}"
               class="sidebar-link {{ request()->routeIs('vouchers.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Vouchers
            </a>
            @endif
            
            <div class="px-6 pt-4 pb-2">
                <p class="text-[0.625rem] uppercase tracking-widest text-text-muted font-semibold">Accounting & Reports</p>
            </div>

            <div x-data="{ open: {{ request()->routeIs('accounting.*') ? 'true' : 'false' }} }" class="px-3">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-text-secondary hover:text-text-primary hover:bg-white/5 transition-colors cursor-pointer text-sm font-medium">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Accounting
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div x-show="open" x-collapse>
                    <div class="py-1 pl-11 space-y-1">
                        <a href="{{ route('accounting.manual-journal') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('accounting.manual-journal') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Manual Journal</a>
                        <a href="{{ route('accounting.chart-of-accounts') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('accounting.chart-of-accounts') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Chart of Accounts</a>
                    </div>
                </div>
            </div>

            <div x-data="{ open: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }" class="px-3 pb-4">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-text-secondary hover:text-text-primary hover:bg-white/5 transition-colors cursor-pointer text-sm font-medium">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                        Reports
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div x-show="open" x-collapse>
                    <div class="py-1 pl-11 space-y-1">
                        <a href="{{ route('reports.sales') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('reports.sales') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Sales Report</a>
                        <a href="{{ route('reports.purchases') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('reports.purchases') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Purchase Report</a>
                        <a href="{{ route('reports.profit-loss') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('reports.profit-loss') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Profit & Loss</a>
                        <a href="{{ route('reports.trial-balance') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('reports.trial-balance') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Trial Balance</a>
                        <a href="{{ route('reports.balance-sheet') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('reports.balance-sheet') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Balance Sheet</a>
                    </div>
                </div>
            </div>

            <div x-data="{ open: {{ request()->routeIs('settings.*') ? 'true' : 'false' }} }" class="px-3 pb-4">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-text-secondary hover:text-text-primary hover:bg-white/5 transition-colors cursor-pointer text-sm font-medium">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.341 3.897c.28-.621.942-1.026 1.659-1.026s1.379.405 1.659 1.026l1.248 2.766 2.977.433c.712.104 1.284.624 1.458 1.32l.74 2.946 2.193 2.1c.513.493.714 1.218.528 1.895l-.946 3.447c-.198.718-.797 1.246-1.536 1.349l-3.328.463-1.637 2.932A1.82 1.82 0 0112 21a1.82 1.82 0 01-1.593-.93l-1.637-2.932-3.328-.463c-.739-.103-1.338-.631-1.536-1.349l-.946-3.447c-.186-.677.015-1.402.528-1.895l2.193-2.1.74-2.946c.174-.696.746-1.216 1.458-1.32l2.977-.433 1.248-2.766z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div x-show="open" x-collapse>
                    <div class="py-1 pl-11 space-y-1">
                        <a href="{{ route('settings.tax-rates') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('settings.tax-rates') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Tax Rates</a>
                        <a href="{{ route('settings.invoice-templates') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('settings.invoice-templates') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Invoice Templates</a>
                        <a href="{{ route('settings.document-numbering') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('settings.document-numbering') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Document Numbering</a>
                        <a href="{{ route('settings.users') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('settings.users') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">User Management</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- User section -->
        @auth
        <div class="mt-auto px-4 pt-4 border-t border-white/5">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center text-accent text-sm font-semibold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-text-primary truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-text-muted truncate">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 rounded-lg text-text-muted hover:text-red-400 hover:bg-red-400/10 transition-colors cursor-pointer" title="Logout">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </aside>

    <!-- ═══ Main Content ═══ -->
    <main class="main-content">
        <!-- Top bar (mobile) -->
        <header class="lg:hidden sticky top-0 z-40 glass px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('logo.png') }}" alt="Cratory" class="w-8 h-8 rounded-lg object-contain">
                <span class="font-bold text-text-primary">Cratory</span>
            </div>

            @auth
            <div class="flex items-center gap-2">
                @livewire('org-switcher')
            </div>
            @endauth
        </header>

        <!-- Page content -->
        <div class="p-4 lg:p-8">
            {{ $slot }}
        </div>
    </main>

    <!-- ═══ Mobile Bottom Tabs ═══ -->
    <nav class="bottom-tabs">
        <a href="{{ route('dashboard') }}" class="bottom-tab {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('invoices.index') }}" class="bottom-tab {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <span>Invoices</span>
        </a>
        <a href="{{ route('inventory.index') }}" class="bottom-tab {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
            <span>Inventory</span>
        </a>
        <a href="#more" class="bottom-tab" x-data @click="$dispatch('toggle-more-menu')">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
            <span>More</span>
        </a>
    </nav>

    <!-- ═══ FAB (Create Button — mobile only) ═══ -->
    <div x-data="{ open: false }" class="lg:hidden">
        <button @click="open = !open" class="fab" :class="{ 'rotate-45': open }" aria-label="Create new">
            <svg class="w-6 h-6 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </button>

        <!-- FAB Menu -->
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" @click.away="open = false" class="fixed bottom-36 right-5 w-56 glass-dropdown p-2 space-y-1" style="z-index: 60;">
            <a href="{{ route('invoices.create', ['type' => 'sales']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 text-text-primary text-sm font-medium transition-colors cursor-pointer">
                <span class="w-8 h-8 rounded-lg bg-accent/15 flex items-center justify-center text-accent">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                </span>
                Sales Invoice
            </a>
            <a href="{{ route('invoices.create', ['type' => 'service']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 text-text-primary text-sm font-medium transition-colors cursor-pointer">
                <span class="w-8 h-8 rounded-lg bg-blue-500/15 flex items-center justify-center text-blue-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                </span>
                Service Invoice
            </a>
            @if(auth()->user()?->currentOrganization?->showsPurchases())
            <a href="{{ route('purchases.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 text-text-primary text-sm font-medium transition-colors cursor-pointer">
                <span class="w-8 h-8 rounded-lg bg-green-500/15 flex items-center justify-center text-green-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                </span>
                Purchase
            </a>
            @endif
            <a href="{{ route('receipts.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 text-text-primary text-sm font-medium transition-colors cursor-pointer">
                <span class="w-8 h-8 rounded-lg bg-primary/15 flex items-center justify-center text-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                </span>
                Receipt
            </a>
        </div>
    </div>

    <!-- ═══ Mobile More Menu ═══ -->
    <div x-data="{ open: false }" 
         @toggle-more-menu.window="open = !open" 
         class="lg:hidden">
        
        <!-- Backdrop -->
        <div x-show="open" 
             x-transition:enter="transition-opacity ease-linear duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-black/60 backdrop-blur-sm" 
             style="z-index: 60;"
             @click="open = false" 
             aria-hidden="true"></div>

        <!-- Menu Panel -->
        <div x-show="open" 
             x-transition:enter="transition ease-in-out duration-300 transform" 
             x-transition:enter-start="translate-y-full" 
             x-transition:enter-end="translate-y-0" 
             x-transition:leave="transition ease-in-out duration-300 transform" 
             x-transition:leave-start="translate-y-0" 
             x-transition:leave-end="translate-y-full" 
             class="fixed inset-x-0 glass-dropdown rounded-t-3xl border-t border-white/10 p-6 pb-6 shadow-[0_-10px_40px_-10px_rgba(0,0,0,0.5)] max-h-[80vh] overflow-y-auto"
             style="bottom: 4rem; z-index: 60;">
            
            <div class="flex items-center gap-3 mb-6 pb-6 border-b border-white/5">
                <div class="w-12 h-12 rounded-full bg-accent/20 flex items-center justify-center text-accent text-lg font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-base font-bold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-sm text-text-muted truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <div class="space-y-2">
                @if(auth()->user()?->currentOrganization?->showsPurchases())
                <a href="{{ route('purchases.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('purchases.*') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-primary hover:bg-white/5' }} text-base font-medium transition-colors">
                    <svg class="w-6 h-6 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                    Purchases
                </a>
                @endif
                
                <a href="{{ route('receipts.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('receipts.*') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-primary hover:bg-white/5' }} text-base font-medium transition-colors">
                    <svg class="w-6 h-6 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>
                    Receipts
                </a>

                @if(auth()->user()?->currentOrganization?->showsPurchases())
                <a href="{{ route('vouchers.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('vouchers.*') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-primary hover:bg-white/5' }} text-base font-medium transition-colors">
                    <svg class="w-6 h-6 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Vouchers
                </a>
                @endif
                
                <a href="{{ route('contacts.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('contacts.*') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-primary hover:bg-white/5' }} text-base font-medium transition-colors">
                    <svg class="w-6 h-6 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    Contacts
                </a>
                
                <div x-data="{ subOpen: {{ request()->routeIs('accounting.*') ? 'true' : 'false' }} }">
                    <button @click="subOpen = !subOpen" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-text-primary hover:bg-white/5 transition-colors cursor-pointer text-base font-medium">
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Accounting
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200 text-text-muted" :class="{ 'rotate-180': subOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div x-show="subOpen" x-collapse>
                        <div class="py-1 pl-14 space-y-1">
                            <a href="{{ route('accounting.manual-journal') }}" class="block px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('accounting.manual-journal') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Manual Journal</a>
                            <a href="{{ route('accounting.chart-of-accounts') }}" class="block px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('accounting.chart-of-accounts') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Chart of Accounts</a>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ subOpen: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }">
                    <button @click="subOpen = !subOpen" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-text-primary hover:bg-white/5 transition-colors cursor-pointer text-base font-medium">
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            Reports
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200 text-text-muted" :class="{ 'rotate-180': subOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div x-show="subOpen" x-collapse>
                        <div class="py-1 pl-14 space-y-1">
                            <a href="{{ route('reports.sales') }}" class="block px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('reports.sales') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Sales Report</a>
                            <a href="{{ route('reports.purchases') }}" class="block px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('reports.purchases') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Purchase Report</a>
                            <a href="{{ route('reports.profit-loss') }}" class="block px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('reports.profit-loss') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Profit & Loss</a>
                            <a href="{{ route('reports.trial-balance') }}" class="block px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('reports.trial-balance') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Trial Balance</a>
                            <a href="{{ route('reports.balance-sheet') }}" class="block px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('reports.balance-sheet') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Balance Sheet</a>
                        </div>
                    </div>
                </div>

                <div x-data="{ subOpen: {{ request()->routeIs('settings.*') ? 'true' : 'false' }} }">
                    <button @click="subOpen = !subOpen" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-text-primary hover:bg-white/5 transition-colors cursor-pointer text-base font-medium">
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.341 3.897c.28-.621.942-1.026 1.659-1.026s1.379.405 1.659 1.026l1.248 2.766 2.977.433c.712.104 1.284.624 1.458 1.32l.74 2.946 2.193 2.1c.513.493.714 1.218.528 1.895l-.946 3.447c-.198.718-.797 1.246-1.536 1.349l-3.328.463-1.637 2.932A1.82 1.82 0 0112 21a1.82 1.82 0 01-1.593-.93l-1.637-2.932-3.328-.463c-.739-.103-1.338-.631-1.536-1.349l-.946-3.447c-.186-.677.015-1.402.528-1.895l2.193-2.1.74-2.946c.174-.696.746-1.216 1.458-1.32l2.977-.433 1.248-2.766z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200 text-text-muted" :class="{ 'rotate-180': subOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div x-show="subOpen" x-collapse>
                        <div class="py-1 pl-14 space-y-1">
                            <a href="{{ route('settings.tax-rates') }}" class="block px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('settings.tax-rates') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Tax Rates</a>
                            <a href="{{ route('settings.invoice-templates') }}" class="block px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('settings.invoice-templates') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Invoice Templates</a>
                            <a href="{{ route('settings.document-numbering') }}" class="block px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('settings.document-numbering') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">Document Numbering</a>
                            <a href="{{ route('settings.users') }}" class="block px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('settings.users') ? 'text-accent bg-accent/10 font-semibold' : 'text-text-secondary hover:text-text-primary hover:bg-white/5' }}">User Management</a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('profile') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-white/5 text-text-primary text-base font-medium transition-colors">
                    <svg class="w-6 h-6 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Profile
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t border-white/5">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-xl text-red-400 hover:bg-red-400/10 text-base font-medium transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
