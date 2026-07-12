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
        'default_payment_info',
        'default_terms_and_conditions',
        'font_choice',
        'watermark_type',
        'watermark_text',
        'watermark_image_path',
        'show_logo',
        'signature_type',
        'signature_text',
        'signature_image_path',
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
            'quantity' => true,
            'rate' => true,
            'discount' => true,
            'tax_details' => true,
            'shipping_address' => true,
            'notes' => true,
            'terms' => true,
            'bank_details' => false,
        ];
    }
}
