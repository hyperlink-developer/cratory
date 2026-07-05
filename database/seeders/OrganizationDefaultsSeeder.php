<?php

namespace Database\Seeders;

use App\Models\InvoiceTemplate;
use App\Models\Organization;
use App\Models\TaxRate;
use Illuminate\Database\Seeder;

class OrganizationDefaultsSeeder extends Seeder
{
    /**
     * Seed default tax rates and invoice templates for a new organization.
     */
    public static function seedForOrganization(Organization $organization): void
    {
        static::seedTaxRates($organization);
        static::seedInvoiceTemplates($organization);
    }

    private static function seedTaxRates(Organization $organization): void
    {
        $rates = [
            ['name' => 'GST 0%', 'percentage' => 0, 'is_gst' => true],
            ['name' => 'GST 5%', 'percentage' => 5, 'is_gst' => true],
            ['name' => 'GST 12%', 'percentage' => 12, 'is_gst' => true],
            ['name' => 'GST 18%', 'percentage' => 18, 'is_gst' => true],
            ['name' => 'GST 28%', 'percentage' => 28, 'is_gst' => true],
        ];

        foreach ($rates as $rate) {
            TaxRate::withoutGlobalScopes()->create([
                'organization_id' => $organization->id,
                ...$rate,
            ]);
        }
    }

    private static function seedInvoiceTemplates(Organization $organization): void
    {
        $templates = [
            [
                'name' => 'Classic',
                'slug' => 'classic',
                'is_default' => true,
                'color_primary' => '#1E293B',
                'color_secondary' => '#475569',
                'logo_position' => 'left',
            ],
            [
                'name' => 'Modern',
                'slug' => 'modern',
                'is_default' => false,
                'color_primary' => '#8B5CF6',
                'color_secondary' => '#F59E0B',
                'logo_position' => 'left',
            ],
            [
                'name' => 'Minimal',
                'slug' => 'minimal',
                'is_default' => false,
                'color_primary' => '#0F172A',
                'color_secondary' => '#64748B',
                'logo_position' => 'center',
            ],
            [
                'name' => 'Bold Color',
                'slug' => 'bold',
                'is_default' => false,
                'color_primary' => '#7C3AED',
                'color_secondary' => '#EC4899',
                'logo_position' => 'right',
            ],
        ];

        $defaultFields = InvoiceTemplate::defaultShowFields();

        foreach ($templates as $template) {
            InvoiceTemplate::withoutGlobalScopes()->create([
                'organization_id' => $organization->id,
                'show_fields' => $defaultFields,
                'font_choice' => 'Plus Jakarta Sans',
                ...$template,
            ]);
        }
    }

    public function run(): void
    {
        // Used statically per-organization, not as a global seeder
    }
}
