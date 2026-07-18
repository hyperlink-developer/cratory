<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GstReportPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'period_type',
        'period_start',
        'period_end',
        'status',
        'finalized_at',
        'finalized_by',
    ];
}
