<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class Gstr1HsnSheet implements FromCollection, WithHeadings, WithTitle
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
            'HSN/SAC',
            'Description',
            'UQC',
            'Total Quantity',
            'Total Value',
            'Taxable Value',
            'Integrated Tax Amount',
            'Central Tax Amount',
            'State/UT Tax Amount',
            'Cess Amount',
            'Total Tax Amount',
        ];
    }

    public function title(): string
    {
        return 'hsn';
    }
}
