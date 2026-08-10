<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomizationOptionVariant extends Model
{
    protected $table = 'customization_option_variants';

    public $timestamps = false;

    protected $fillable = [
        'customization_option_id',
        'variant_type',
        'display_name',
        'price',
        'hex_color',
        'image_url',
        'is_active',
        'sort_order',
    ];

    public function option()
    {
        return $this->belongsTo(CustomizationOption::class, 'customization_option_id');
    }
}
