<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'game_id',
        'message',
        'is_read',
    ];
}
