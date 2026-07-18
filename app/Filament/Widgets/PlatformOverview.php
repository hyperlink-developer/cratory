<?php

namespace App\Filament\Widgets;

use App\Models\Organization;
use App\Models\OrganizationSubscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $activeTenants = Organization::active()->count();
        $totalSignupsThisMonth = Organization::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Calculate MRR
        $mrr = 0;
        $activeSubscriptions = OrganizationSubscription::with('plan')
            ->where('status', 'active')
            ->get();

        foreach ($activeSubscriptions as $subscription) {
            if ($subscription->plan) {
                // Approximate MRR for yearly plans by dividing by 12, or just use monthly price if they are monthly.
                // Assuming all are monthly for simplicity, or we can just sum price_monthly.
                $mrr += $subscription->plan->price_monthly;
            }
        }

        return [
            Stat::make('Active Tenants', $activeTenants)
                ->description('Total active organizations on the platform')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),
            Stat::make('Signups This Month', $totalSignupsThisMonth)
                ->description('New organizations this month')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info'),
            Stat::make('Estimated MRR', '₹' . number_format($mrr, 2))
                ->description('Monthly Recurring Revenue')
                ->descriptionIcon('heroicon-m-currency-rupee')
                ->color('success'),
        ];
    }
}
