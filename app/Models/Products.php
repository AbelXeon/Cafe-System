<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\categories;
use App\Models\OrderItems;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'catagory_id',
        'name',
        'discribtion',
        'price',
        'image',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(categories::class, 'catagory_id');
    }

    public function extras()
    {
        return $this->belongsToMany(Extras::class, 'product_extras', 'product_id', 'extar_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReviews::class);
    }
}