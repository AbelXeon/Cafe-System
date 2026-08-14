<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryReviews extends Model
{
    /** @use HasFactory<\Database\Factories\DeliveryReviewsFactory> */
    use HasFactory;

     protected $fillable = ['user_id', 'order_id', 'delivery_user_id', 'rating', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    public function deliveryUser()
    {
        return $this->belongsTo(User::class, 'delivery_user_id');
    }
}
