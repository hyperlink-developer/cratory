<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class Gstr1B2bSheet implements FromCollection, WithHeadings, WithTitle
{
    protected Collection $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'GSTIN/UIN of Recipient',
            'Receiver Name',
            'Invoice Number',
            'Invoice date',
            'Invoice Value',
            'Place Of Supply',
            'Reverse Charge',
            'Invoice Type',
            'Rate',
            'Taxable Value',
            'Tax Amount',
        ];
    }

    public function title(): string
    {
        return 'b2b';
    }
}
