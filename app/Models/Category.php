<?php

namespace App\Models;

use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories'; // matches your existing migration table name

    protected $fillable = ['name', 'type', 'description'];

    public function products()
    {
        return $this->hasMany(Products::class, 'category_id');
    }
}