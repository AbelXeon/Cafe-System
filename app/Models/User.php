<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\DeliveryReviews;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function orders()
    {
        return $this->hasMany(Orders::class);
    }

    public function savedLocations()
    {
        return $this->hasMany(SavedLocations::class);
    }

    public function deliveryProfile()
    {
        return $this->hasOne(DeliveryProfiles::class);
    }

    public function deliveries()
    {
        return $this->hasMany(OrderDeliveries::class, 'delivery_user_id');
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReviews::class);
    }

    public function deliveryReviews()
    {
        return $this->hasMany(DeliveryReviews::class);
    }

    public function adminActions()
    {
        return $this->hasMany(AdminAction::class, 'admin_id');
    }

    public function statusChanges()
    {
        return $this->hasMany(OrderStatusHistory::class, 'changed_by');
    }
}