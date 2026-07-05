<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class InvoiceTemplate extends Model
{
    use BelongsToOrganization, HasUuid;

    protected $fillable = [
        'organization_id',
        'name',
        'slug',
        'is_default',
        'color_primary',
        'color_secondary',
        'logo_position',
        'show_fields',
        'header_note',
        'footer_note',
        'font_choice',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'show_fields' => 'array',
        ];
    }

    public static function defaultShowFields(): array
    {
        return [
            'hsn' => true,
            'discount' => true,
            'shipping_address' => true,
            'notes' => true,
            'terms' => true,
            'bank_details' => false,
        ];
    }
}
