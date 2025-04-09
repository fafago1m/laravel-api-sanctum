<?php

namespace Database\Seeders;

use App\Models\Score;
use App\Models\User;
use App\Models\Game;
use App\Models\Games;
use App\Models\Scores;
use Illuminate\Database\Seeder;

class ScoresSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('user')->get();
        $games = Games::all();

        if ($users->isEmpty() || $games->isEmpty()) {
            $this->command->warn('Tidak ada user atau game untuk membuat skor.');
            return;
        }

        foreach ($games as $game) {
            foreach ($users->random(min(3, $users->count())) as $user) {
                Scores::create([
                    'user_id' => $user->id,
                    'game_id' => $game->id,
                    'score' => rand(100, 9999),
                ]);
            }
        }
    }
}
