<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresetItem extends Model
{
    protected $table = 'preset_items';

    public $timestamps = false;

    protected $fillable = ['preset_id', 'flower_id', 'quantity'];

    public function preset()
    {
        return $this->belongsTo(CustomizationPreset::class, 'preset_id');
    }

    public function flower()
    {
        return $this->belongsTo(Product::class, 'flower_id');
    }
}
