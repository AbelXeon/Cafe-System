<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDeliveries extends Model
{
    /** @use HasFactory<\Database\Factories\OrderDeliveriesFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'delivery_user_id',
        'status',
        'assigned_at',
        'accepted_at',
        'picked_up_at',
        'delivered_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'accepted_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    public function deliveryUser()
    {
        return $this->belongsTo(User::class, 'delivery_user_id');
    }
}
