<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    /** @use HasFactory<\Database\Factories\OrdersFactory> */
    use HasFactory;
     protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_type',
        'special_note',
        'subtotal',
        'extra_total',
        'delivery_fee',
        'total_amount',
        'status',
        'delivery_address',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'extra_total' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function delivery()
    {
        return $this->hasOne(OrderDeliveries::class);
    }

    public function payment()
    {
        return $this->hasOne(Payments::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReviews::class);
    }

    public function deliveryReview()
    {
        return $this->hasOne(DeliveryReviews::class);
    }
}
