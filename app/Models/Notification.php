<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'game_id',
        'message',
        'is_read',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function game()
    {
        return $this->belongsTo(Games::class, 'game_id');
    }
}
