<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedCustomization extends Model
{
    protected $table = 'saved_customizations';

    public $timestamps = false;

    protected $fillable = ['customer_id', 'design_name', 'design_data', 'total_price'];

    protected $casts = [
        'design_data' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
