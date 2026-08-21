<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductExtra extends Model
{
    protected $fillable = [
        'product_id',
        'extra_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function extra()
    {
        return $this->belongsTo(Extra::class);
    }
}
