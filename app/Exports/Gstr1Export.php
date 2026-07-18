<?php

namespace App\Exports;

use App\Services\GST\Gstr1ReportService;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\Gstr1B2bSheet;
use App\Exports\Sheets\Gstr1B2cSheet;
use App\Exports\Sheets\Gstr1HsnSheet;

class Gstr1Export implements WithMultipleSheets
{
    use Exportable;

    protected Gstr1ReportService $service;

    public function __construct(Gstr1ReportService $service)
    {
        $this->service = $service;
    }

    public function sheets(): array
    {
        return [
            new Gstr1B2bSheet($this->service->getB2bInvoices()),
            new Gstr1B2cSheet($this->service->getB2cInvoices()),
            new Gstr1HsnSheet($this->service->getHsnSummary()),
        ];
    }
}
