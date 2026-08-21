<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model
{
    protected $fillable = [
        'order_id',
        'delivery_user_id',
        'status',
        'assigned_at',
        'accepted_at',
        'picked_up_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'accepted_at' => 'datetime',
            'picked_up_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryUser()
    {
        return $this->belongsTo(
            User::class,
            'delivery_user_id'
        );
    }
}