<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Games;
use Illuminate\Support\Facades\Storage;

class DeveloperController extends Controller
{
   
    public function getUploadedGames()
    {
        $developerId = auth()->user()->id;

        
        $games = Games::where('developer_id', $developerId)->get();

        return response()->json($games);
    }

    public function uploadGame(Request $request)
    {
      
        $validated = $request->validate([
            'file' => 'required|file|mimes:zip|max:10240', 
            'title' => 'required|string|max:255',
        ]);


        $file = $request->file('file');
        $filePath = 'games/' . time() . '_' . $file->getClientOriginalName();
        $fileUrl = Storage::disk('minio')->putFileAs('games', $file, $filePath);

        
        $game = Games::create([
            'title' => $request->input('title'),
            'file_url' => $fileUrl,
            'developer_id' => auth()->user()->id,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Game uploaded successfully, awaiting approval.',
            'game_id' => $game->id,
            'file_url' => Storage::disk('minio')->url($fileUrl),
        ]);
    }
}
