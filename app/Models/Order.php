<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
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

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'extra_total' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveries()
    {
        return $this->hasMany(OrderDelivery::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function deliveryReviews()
    {
        return $this->hasMany(DeliveryReview::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
}
