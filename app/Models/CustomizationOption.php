<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomizationOption extends Model
{
    protected $table = 'customization_options';

    public $timestamps = false;

    protected $fillable = [
        'type',
        'name',
        'display_name',
        'price',
        'image_url',
        'category',
        'hex_color',
        'is_active',
        'sort_order',
    ];
}
