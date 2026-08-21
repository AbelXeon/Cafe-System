<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'target_type',
        'target_id',
        'description',
    ];

    public function admin()
    {
        return $this->belongsTo(
            User::class,
            'admin_id'
        );
    }
}
