<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    /** @use HasFactory<\Database\Factories\AdminActionFactory> */
    use HasFactory;

    protected $fillable = ['admin_id', 'action', 'description'];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
