<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    protected $fillable = [
        'name',
        'price',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_available' => 'boolean',
        ];
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_extras'
        );
    }

    public function orderItemExtras()
    {
        return $this->hasMany(OrderItemExtra::class);
    }
}