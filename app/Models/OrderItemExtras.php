<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemExtras extends Model
{
    /** @use HasFactory<\Database\Factories\OrderItemExtrasFactory> */
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'extra_id',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItems::class);
    }

    public function extra()
    {
        return $this->belongsTo(Extra::class);
    }
}
