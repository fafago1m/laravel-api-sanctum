<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scores extends Model
{
    // Nama tabel secara default adalah 'scores' (plural dari Score),
    // tapi karena nama model kamu 'Scores' (plural), kita sebutkan eksplisit.
    protected $table = 'scores';

    // Kolom yang boleh diisi massal (via create atau fill)
    protected $fillable = [
        'user_id',
        'game_id',
        'score',
    ];

    // Pastikan timestamps aktif (default true, ini opsional jika tidak kamu ubah sebelumnya)
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Games::class);
    }
}
