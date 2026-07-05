<?php

namespace App\Services;

use App\Models\DocumentSequence;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DocumentNumberGenerator
{
    /**
     * Generate a gap-free, race-safe document number.
     *
     * Format: {ORG_PREFIX}-{DOC_TYPE_CODE}-{FY}-{SEQUENCE}
     * Example: CRT-INV-2526-0001
     */
    public function generate(Organization $organization, string $documentType): string
    {
        $financialYear = $this->getFinancialYear($organization);
        $prefix = $organization->invoice_prefix;

        $nextNumber = DB::transaction(function () use ($organization, $documentType, $financialYear) {
            $sequence = DocumentSequence::withoutGlobalScopes()
                ->where('organization_id', $organization->id)
                ->where('document_type', $documentType)
                ->where('financial_year', $financialYear)
                ->lockForUpdate()
                ->first();

            if (!$sequence) {
                $sequence = DocumentSequence::withoutGlobalScopes()->create([
                    'organization_id' => $organization->id,
                    'document_type' => $documentType,
                    'financial_year' => $financialYear,
                    'last_number' => 0,
                ]);
                // Re-lock after creation
                $sequence = DocumentSequence::withoutGlobalScopes()
                    ->where('id', $sequence->id)
                    ->lockForUpdate()
                    ->first();
            }

            $sequence->increment('last_number');

            return $sequence->last_number;
        });

        return sprintf(
            '%s-%s-%s-%04d',
            $prefix,
            $documentType,
            $financialYear,
            $nextNumber
        );
    }

    /**
     * Peek at the next document number without incrementing the sequence.
     */
    public function peek(Organization $organization, string $documentType): string
    {
        $financialYear = $this->getFinancialYear($organization);
        $prefix = $organization->invoice_prefix;

        $sequence = DocumentSequence::withoutGlobalScopes()
            ->where('organization_id', $organization->id)
            ->where('document_type', $documentType)
            ->where('financial_year', $financialYear)
            ->first();

        $nextNumber = $sequence ? $sequence->last_number + 1 : 1;

        return sprintf(
            '%s-%s-%s-%04d',
            $prefix,
            $documentType,
            $financialYear,
            $nextNumber
        );
    }

    /**
     * Get financial year string (e.g., "2526" for April 2025 – March 2026).
     */
    private function getFinancialYear(Organization $organization): string
    {
        $now = Carbon::now();
        $fyStartMonth = $organization->financial_year_start_month;

        $startYear = $now->month >= $fyStartMonth ? $now->year : $now->year - 1;
        $endYear = $startYear + 1;

        return substr((string) $startYear, -2) . substr((string) $endYear, -2);
    }
}
