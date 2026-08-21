<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'role_id',
        'fullname',
        'phone',
        'email',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function savedLocations()
    {
        return $this->hasMany(SavedLocation::class);
    }

    public function deliveryProfile()
    {
        return $this->hasOne(DeliveryProfile::class);
    }

    public function deliveries()
    {
        return $this->hasMany(OrderDelivery::class, 'delivery_user_id');
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function deliveryReviews()
    {
        return $this->hasMany(DeliveryReview::class);
    }

    public function changedOrderStatuses()
    {
        return $this->hasMany(
            OrderStatusHistory::class,
            'changed_by_user_id'
        );
    }

    public function adminActions()
    {
        return $this->hasMany(AdminAction::class, 'admin_id');
    }
}