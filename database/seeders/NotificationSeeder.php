<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Models\Game;
use App\Models\Games;
use App\Models\Notifications;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::take(5)->get();
        $games = Games::take(3)->get();

        foreach ($users as $user) {
            Notifications::create([
                'from_user_id' => 1,  
                'to_user_id' => $user->id,
                'game_id' => $games->random()->id,
                'message' => 'Game baru telah disetujui dan siap dimainkan!',
                'is_read' => false,
            ]);

            Notifications::create([
                'from_user_id' => 1,
                'to_user_id' => $user->id,
                'game_id' => null,
                'message' => 'Akun Anda telah diverifikasi oleh admin.',
                'is_read' => true,
            ]);
        }
    }
}
