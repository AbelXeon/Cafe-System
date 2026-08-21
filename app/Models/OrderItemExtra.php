<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemExtra extends Model
{
    protected $fillable = [
        'order_item_id',
        'extra_id',
        'quantity',
        'price',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function extra()
    {
        return $this->belongsTo(Extra::class);
    }
}
