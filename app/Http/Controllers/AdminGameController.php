
<?php

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Games;
use App\Models\User;
use App\Notifications\GameApprovedNotification;
use Illuminate\Http\Request;

class AdminGameController extends Controller
{
    public function pendingGames()
    {
        $games = Games::where('status', 'pending')->with('user')->get();

        return response()->json($games);
    }

    public function approveGame($gameId)
{
    $game = Games::findOrFail($gameId);

   
    $game->status = 'published';
    $game->save();

    return response()->json(['message' => 'Game has been approved and published']);
}

}
