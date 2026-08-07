<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GcashPayment extends Model
{
    protected $table = 'gcash_payments';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'reference_number',
        'amount',
        'payment_type',
        'screenshot_path',
        'verified',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
