<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scores;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'score' => 'required|integer',
            'user_id' => 'required|exists:users,id',
            'game_id' => 'required|exists:games,id',
        ]);
    
        $score = Scores::create([
            'user_id' => $request->user_id,
            'game_id' => $request->game_id,
            'score' => $request->score,
        ]);
    
        return response()->json([
            'message' => 'Score saved successfully',
            'data' => $score,
        ]);
    }
    
    
}
