<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    use HasFactory;

    // Define relationships

    /**
     * The user who owns the game (player).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The developer of the game.
     */
    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    protected $fillable = [
        'developer_id',
        'title',
        'slug',
        'description',
        'thumbnail_path',
        'zip_path',
        'extracted_path',
        'category_id',
        'status',
        'play_count',
        'user_id',
    ];
}
