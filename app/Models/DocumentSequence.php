<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;

class DocumentSequence extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'document_type',
        'financial_year',
        'last_number',
    ];

    protected function casts(): array
    {
        return [
            'last_number' => 'integer',
        ];
    }
}
