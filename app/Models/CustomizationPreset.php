<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomizationPreset extends Model
{
    protected $table = 'customization_presets';

    public $timestamps = false;

    protected $fillable = ['name', 'description', 'base_price', 'image_url', 'is_active'];

    public function presetItems()
    {
        return $this->hasMany(PresetItem::class, 'preset_id');
    }
}
