<?php
namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\Scores;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function show($gameId)
    {
        $scores = Scores::where('game_id', $gameId)
            ->with('user:id,name')
            ->orderByDesc('score')
            ->take(10)
            ->get()
            ->map(function ($score) {
                return [
                    'user_id' => $score->user_id,
                    'user_name' => $score->user->name,
                    'score' => $score->score,
                ];
            });

        return response()->json(['data' => $scores]);
    }
}
