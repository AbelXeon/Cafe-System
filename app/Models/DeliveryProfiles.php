<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryProfiles extends Model
{
    /** @use HasFactory<\Database\Factories\DeliveryProfilesFactory> */
    use HasFactory;
      protected $fillable = ['user_id', 'is_online'];

    protected $casts = [
        'is_online' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
