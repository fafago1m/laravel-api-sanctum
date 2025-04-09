<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GamesSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Games::create([
            'developer_id' => 2, 
            'title' => 'Amazing Adventure',
            'slug' => Str::slug('Amazing Adventure'),
            'description' => 'Petualangan seru menjelajah dunia baru.',
            'thumbnail_path' => 'thumbnails/amazing-adventure.jpg',
            'zip_path' => 'games/amazing-adventure.zip',
            'extracted_path' => 'games/amazing-adventure',
            'category_id' => 1,
            'status' => 'published',
            'play_count' => 0,
            'approved_at' => now(),
        ]);
    }
}
