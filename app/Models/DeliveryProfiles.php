<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryProfile extends Model
{
    protected $fillable = [
        'user_id',
        'profile_image',
        'current_latitude',
        'current_longitude',
        'is_online',
    ];

    protected function casts(): array
    {
        return [
            'current_latitude' => 'decimal:7',
            'current_longitude' => 'decimal:7',
            'is_online' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
