<?php

namespace App\Services\GST;

use Illuminate\Support\Str;

class MockGspProvider implements GspProviderInterface
{
    public function generateIrn(array $payload): array
    {
        // Simulate API latency
        usleep(500000); 

        return [
            'irn' => hash('sha256', Str::uuid()),
            'ack_no' => (string) rand(10000000000, 99999999999),
            'ack_date' => now()->format('Y-m-d H:i:s'),
            'signed_qr_code' => 'mock_qr_code_eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c',
            'signed_invoice' => 'mock_signed_invoice_data',
        ];
    }

    public function cancelIrn(string $irn, string $cancelReason, string $cancelRemark): bool
    {
        usleep(300000);
        return true;
    }

    public function generateEWayBill(array $payload): array
    {
        usleep(400000);

        return [
            'eway_bill_number' => (string) rand(100000000000, 999999999999),
            'eway_bill_date' => now()->format('Y-m-d H:i:s'),
            'valid_until' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ];
    }
}
