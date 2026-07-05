<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'account_group_id',
        'name',
        'code',
        'description',
        'is_system',
        'is_active',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function accountGroup()
    {
        return $this->belongsTo(AccountGroup::class);
    }

    public function journalEntryLines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }
}
