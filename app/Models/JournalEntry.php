<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'date',
        'reference_number',
        'description',
        'journalable_type',
        'journalable_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function journalable()
    {
        return $this->morphTo();
    }

    public function lines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }
}
