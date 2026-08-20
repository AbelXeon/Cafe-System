<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductExtras extends Model
{
    /** @use HasFactory<\Database\Factories\ProductExtrasFactory> */
    use HasFactory;
    

    protected $fillable = ['extra_id', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function extra()
    {
        return $this->belongsTo(Extra::class, 'extra_id');
    }
}
