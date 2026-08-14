<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedLocations extends Model
{
    /** @use HasFactory<\Database\Factories\SavedlocationsFactory> */
    use HasFactory;
    protected $table = 'saved_locations';

    protected $fillable = ['user_id', 'name', 'address', 'latitude', 'longitude'];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
