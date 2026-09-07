<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Order;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('order.{orderId}.chat', function ($user, $orderId) {
    $order = Order::find($orderId);
 
    if (! $order) {
        return false;
    }
 
    return $user->id === $order->user_id || $user->id === $order->delivery_user_id;
});