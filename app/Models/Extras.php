<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extras extends Model
{
    use HasFactory;

    protected $table = 'extras';

    protected $fillable = ['name', 'price', 'is_available'];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function products()
    {
        return $this->belongsToMany(Products::class, 'product_extras', 'extra_id', 'product_id');
    }
}