<?php

namespace App\Http\Controllers;

use App\Models\Games;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\GameApprovedNotification;

class AdminController extends Controller
{
    public function notifyDeveloper(Request $request)
    {
        $request->validate([
            'developer_id' => 'required|exists:users,id',
            'message' => 'required|string|max:255',
        ]);

        $developer = User::findOrFail($request->developer_id);
        $developer->notify(new GameApprovedNotification($request->message));

        return response()->json(['message' => 'Notifikasi dikirim ke developer.']);
    }



    public function getPendingGames()
{
  
    $pendingGames = Games::with('developer:id,name')->where('status', 'pending')->get();

    return response()->json($pendingGames);
}



    public function getDevelopers()
    {
        
        $developers = User::role('developer')->get(); 
        
        return response()->json($developers);
    }

    public function approveGame($gameId)
{
    $game = Games::findOrFail($gameId);

    $game->status = 'published';
    $game->save();

    Notification::create([
        'from_user_id' => auth()->id(), 
        'to_user_id' => $game->user_id, 
        'game_id' => $game->id,
        'message' => "Game '{$game->title}' telah disetujui dan dipublikasikan oleh admin.",
        'is_read' => 0,
    ]);

    return response()->json(['message' => 'Game has been approved and notification sent']);
}

public function sendNotification(Request $request)
{
    $validated = $request->validate([
        'developer_id' => 'required|exists:users,id',
        'message' => 'required|string',
        'game_id' => 'nullable|exists:games,id',
    ]);

    Notification::create([
        'from_user_id' => auth()->id(),
        'to_user_id' => $validated['developer_id'],
        'game_id' => $validated['game_id'] ?? null, 
        'message' => $validated['message'],
        'is_read' => 0,
    ]);

    return response()->json(['message' => 'Notifikasi berhasil dikirim']);
}


public function indexalluser()
    {

        $users = User::all();


        return response()->json($users);
    }


}