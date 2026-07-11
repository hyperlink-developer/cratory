<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\InvoiceTemplate;
use App\Models\Organization;
use App\Models\TaxRate;
use App\Models\UnitOfMeasure;
use Illuminate\Database\Seeder;

class OrganizationDefaultsSeeder extends Seeder
{
    /**
     * Seed default settings, tax rates, UoMs, and invoice templates for a new organization.
     */
    public static function seedForOrganization(Organization $organization): void
    {
        static::seedTaxRates($organization);
        static::seedUnitOfMeasures($organization);
        static::seedInvoiceTemplates($organization);
        static::seedChartOfAccounts($organization);
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

    private static function seedUnitOfMeasures(Organization $organization): void
    {
        $uoms = [
            ['name' => 'Pieces', 'abbreviation' => 'pcs'],
            ['name' => 'Numbers', 'abbreviation' => 'nos'],
            ['name' => 'Kilograms', 'abbreviation' => 'kg'],
            ['name' => 'Grams', 'abbreviation' => 'g'],
            ['name' => 'Liters', 'abbreviation' => 'l'],
            ['name' => 'Meters', 'abbreviation' => 'm'],
            ['name' => 'Boxes', 'abbreviation' => 'box'],
            ['name' => 'Packets', 'abbreviation' => 'pkt'],
            ['name' => 'Dozen', 'abbreviation' => 'dz'],
            ['name' => 'Square Meters', 'abbreviation' => 'sqm'],
            ['name' => 'Metric Tonnes', 'abbreviation' => 'mt'],
        ];

        foreach ($uoms as $uom) {
            UnitOfMeasure::withoutGlobalScopes()->create([
                'organization_id' => $organization->id,
                ...$uom,
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

    private static function seedChartOfAccounts(Organization $organization): void
    {
        // Indian standard account groups (Tally-style logic)
        $groups = [
            // Assets
            ['name' => 'Fixed Assets', 'code' => 'ASSET-FIXED', 'type' => 'asset', 'is_system' => true],
            ['name' => 'Bank Accounts', 'code' => 'ASSET-BANK', 'type' => 'asset', 'is_system' => true],
            ['name' => 'Cash-in-hand', 'code' => 'ASSET-CASH', 'type' => 'asset', 'is_system' => true],
            ['name' => 'Sundry Debtors', 'code' => 'ASSET-DEBTORS', 'type' => 'asset', 'is_system' => true],
            ['name' => 'Stock-in-hand', 'code' => 'ASSET-STOCK', 'type' => 'asset', 'is_system' => true],
            ['name' => 'Current Assets', 'code' => 'ASSET-CURRENT', 'type' => 'asset', 'is_system' => true],

            // Liabilities
            ['name' => 'Capital Account', 'code' => 'LIAB-CAPITAL', 'type' => 'equity', 'is_system' => true],
            ['name' => 'Sundry Creditors', 'code' => 'LIAB-CREDITORS', 'type' => 'liability', 'is_system' => true],
            ['name' => 'Duties & Taxes', 'code' => 'LIAB-TAXES', 'type' => 'liability', 'is_system' => true],
            ['name' => 'Provisions', 'code' => 'LIAB-PROVISIONS', 'type' => 'liability', 'is_system' => true],
            ['name' => 'Current Liabilities', 'code' => 'LIAB-CURRENT', 'type' => 'liability', 'is_system' => true],
            ['name' => 'Loans (Liability)', 'code' => 'LIAB-LOANS', 'type' => 'liability', 'is_system' => true],

            // Revenue (Income)
            ['name' => 'Sales Accounts', 'code' => 'REV-SALES', 'type' => 'revenue', 'is_system' => true],
            ['name' => 'Direct Incomes', 'code' => 'REV-DIRECT', 'type' => 'revenue', 'is_system' => true],
            ['name' => 'Indirect Incomes', 'code' => 'REV-INDIRECT', 'type' => 'revenue', 'is_system' => true],

            // Expenses
            ['name' => 'Purchase Accounts', 'code' => 'EXP-PURCHASE', 'type' => 'expense', 'is_system' => true],
            ['name' => 'Direct Expenses', 'code' => 'EXP-DIRECT', 'type' => 'expense', 'is_system' => true],
            ['name' => 'Indirect Expenses', 'code' => 'EXP-INDIRECT', 'type' => 'expense', 'is_system' => true],
        ];

        $createdGroups = [];
        foreach ($groups as $group) {
            $createdGroups[$group['code']] = AccountGroup::create([
                'organization_id' => $organization->id,
                ...$group,
            ]);
        }

        // Default accounts for these groups
        $accounts = [
            // Cash and Bank
            ['account_group_id' => $createdGroups['ASSET-CASH']->id, 'name' => 'Main Cash', 'code' => '1000', 'is_system' => true],
            ['account_group_id' => $createdGroups['ASSET-BANK']->id, 'name' => 'Default Bank Account', 'code' => '1010', 'is_system' => true],

            // Debtors & Creditors
            ['account_group_id' => $createdGroups['ASSET-DEBTORS']->id, 'name' => 'Accounts Receivable', 'code' => '1200', 'is_system' => true],
            ['account_group_id' => $createdGroups['LIAB-CREDITORS']->id, 'name' => 'Accounts Payable', 'code' => '2000', 'is_system' => true],

            // Inventory & Stock
            ['account_group_id' => $createdGroups['ASSET-STOCK']->id, 'name' => 'Inventory Asset', 'code' => '1300', 'is_system' => true],

            // Taxes (GST)
            ['account_group_id' => $createdGroups['LIAB-TAXES']->id, 'name' => 'Output CGST', 'code' => '2100', 'is_system' => true],
            ['account_group_id' => $createdGroups['LIAB-TAXES']->id, 'name' => 'Output SGST', 'code' => '2101', 'is_system' => true],
            ['account_group_id' => $createdGroups['LIAB-TAXES']->id, 'name' => 'Output IGST', 'code' => '2102', 'is_system' => true],
            ['account_group_id' => $createdGroups['LIAB-TAXES']->id, 'name' => 'Input CGST', 'code' => '2110', 'is_system' => true],
            ['account_group_id' => $createdGroups['LIAB-TAXES']->id, 'name' => 'Input SGST', 'code' => '2111', 'is_system' => true],
            ['account_group_id' => $createdGroups['LIAB-TAXES']->id, 'name' => 'Input IGST', 'code' => '2112', 'is_system' => true],
            ['account_group_id' => $createdGroups['LIAB-TAXES']->id, 'name' => 'TDS Payable', 'code' => '2120', 'is_system' => true],

            // Equity
            ['account_group_id' => $createdGroups['LIAB-CAPITAL']->id, 'name' => 'Owner\'s Equity', 'code' => '3000', 'is_system' => true],
            ['account_group_id' => $createdGroups['LIAB-CAPITAL']->id, 'name' => 'Retained Earnings', 'code' => '3100', 'is_system' => true],

            // Revenue
            ['account_group_id' => $createdGroups['REV-SALES']->id, 'name' => 'Sales Revenue', 'code' => '4000', 'is_system' => true],
            ['account_group_id' => $createdGroups['REV-SALES']->id, 'name' => 'Service Revenue', 'code' => '4010', 'is_system' => true],
            ['account_group_id' => $createdGroups['REV-INDIRECT']->id, 'name' => 'Discount Received', 'code' => '4100', 'is_system' => true],
            ['account_group_id' => $createdGroups['REV-INDIRECT']->id, 'name' => 'Interest Income', 'code' => '4200', 'is_system' => true],

            // Expenses
            ['account_group_id' => $createdGroups['EXP-PURCHASE']->id, 'name' => 'Cost of Goods Sold (COGS)', 'code' => '5000', 'is_system' => true],
            ['account_group_id' => $createdGroups['EXP-DIRECT']->id, 'name' => 'Wages & Direct Labor', 'code' => '5100', 'is_system' => true],
            ['account_group_id' => $createdGroups['EXP-INDIRECT']->id, 'name' => 'Discount Given', 'code' => '6000', 'is_system' => true],
            ['account_group_id' => $createdGroups['EXP-INDIRECT']->id, 'name' => 'Bank Charges', 'code' => '6100', 'is_system' => true],
            ['account_group_id' => $createdGroups['EXP-INDIRECT']->id, 'name' => 'Salaries & Staff Welfare', 'code' => '6200', 'is_system' => true],
            ['account_group_id' => $createdGroups['EXP-INDIRECT']->id, 'name' => 'Rent Expense', 'code' => '6300', 'is_system' => true],
            ['account_group_id' => $createdGroups['EXP-INDIRECT']->id, 'name' => 'Utilities & Telephone', 'code' => '6400', 'is_system' => true],
            ['account_group_id' => $createdGroups['EXP-INDIRECT']->id, 'name' => 'Advertising & Marketing', 'code' => '6500', 'is_system' => true],
            ['account_group_id' => $createdGroups['EXP-INDIRECT']->id, 'name' => 'Travel & Conveyance', 'code' => '6600', 'is_system' => true],
            ['account_group_id' => $createdGroups['EXP-INDIRECT']->id, 'name' => 'Office Supplies', 'code' => '6700', 'is_system' => true],
            ['account_group_id' => $createdGroups['EXP-INDIRECT']->id, 'name' => 'Printing & Stationery', 'code' => '6800', 'is_system' => true],
            ['account_group_id' => $createdGroups['EXP-INDIRECT']->id, 'name' => 'Depreciation Expense', 'code' => '6900', 'is_system' => true],
        ];

        foreach ($accounts as $account) {
            Account::create([
                'organization_id' => $organization->id,
                ...$account,
            ]);
        }
    }

    public function run(): void
    {
        // Used statically per-organization, not as a global seeder
    }
}
