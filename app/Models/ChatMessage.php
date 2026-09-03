<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDelivery;

class ChatMessage extends Model
{
    protected $fillable = ['order_delivery_id', 'sender_id', 'message'];

    public function orderDelivery()
    {
        return $this->belongsTo(OrderDelivery::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}