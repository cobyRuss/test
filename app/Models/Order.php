<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public $timestamps = true;

    protected $fillable = [
        'order_number',
        'customer_id',
        'sender_phone',
        'recipient_name',
        'recipient_phone',
        'total_amount',
        'delivery_fee',
        'down_payment',
        'remaining_balance',
        'payment_method',
        'payment_status',
        'order_status',
        'delivery_address',
        'municipality',
        'recipient_barangay',
        'recipient_street',
        'delivery_date',
        'special_instructions',
        'message_for_recipient',
        'sender_anonymous',
        'cancel_reason',
        'cancel_note',
        'cancelled_at',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'cancelled_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function gcashPayments()
    {
        return $this->hasMany(GcashPayment::class, 'order_id');
    }
}
