<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramAccount extends Model
{
    protected $fillable = [
        'user_id',
        'telegram_user_id',
        'telegram_username',
        'telegram_first_name',
        'linked_at',
        'last_login_at',
    ];

    protected function casts(): array
    {
        return [
            'linked_at'     => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}