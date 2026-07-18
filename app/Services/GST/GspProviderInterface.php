<?php

namespace App\Services\GST;

interface GspProviderInterface
{
    /**
     * Generate an Invoice Reference Number (IRN)
     *
     * @param array $payload NIC standard E-Invoice payload
     * @return array Contains 'irn', 'ack_no', 'ack_date', 'signed_qr_code', 'signed_invoice'
     */
    public function generateIrn(array $payload): array;

    /**
     * Cancel an IRN
     *
     * @param string $irn
     * @param string $cancelReason
     * @param string $cancelRemark
     * @return bool
     */
    public function cancelIrn(string $irn, string $cancelReason, string $cancelRemark): bool;

    /**
     * Generate an E-Way Bill
     *
     * @param array $payload NIC standard E-Way Bill payload
     * @return array Contains 'eway_bill_number', 'eway_bill_date', 'valid_until'
     */
    public function generateEWayBill(array $payload): array;
}
