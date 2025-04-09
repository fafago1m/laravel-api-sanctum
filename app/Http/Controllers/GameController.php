<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Games;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\File;

class GameController extends Controller
{
   
    public function show($slug)
    {
        
        $game = Games::where('slug', $slug)
                     ->where('status', 'published')
                     ->first();

        if (!$game) {
            return response()->json(['message' => 'Game not found or not published'], 404);
        }

        return response()->json(['data' => $game]);
    }


    public function recommendations($slug)
    {
      
        $game = Games::where('slug', $slug)
                     ->where('status', 'published')
                     ->first();

        if (!$game) {
            return response()->json(['message' => 'Game not found or not published'], 404);
        }

    
        $recommendations = Games::where('category_id', $game->category_id)
            ->where('id', '!=', $game->id)
            ->where('status', 'published')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return response()->json(['data' => $recommendations]);
    }

    
    public function index()
    {
        $games = Games::where('developer_id', auth()->id())->get();
        return response()->json($games);
    }

    
    public function showDeveloperGame($id)
    {
        $game = Games::where('developer_id', auth()->id())->findOrFail($id);
        return response()->json($game);
    }

    
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'zip' => 'required|file|mimes:zip',
        'category_id' => 'required|exists:categories,id',
    ]);

    $thumbnailPath = null;
    if ($request->hasFile('thumbnail')) {
        $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
    }


    $zipPath = $request->file('zip')->store('games/zip', 'public');


    $randomFolder = Str::random(10);
    $extractedPath = 'games/' . $randomFolder;
    $storagePath = storage_path('app/public/' . $extractedPath);

    File::makeDirectory($storagePath, 0755, true, true);

    $zip = new ZipArchive;
    if ($zip->open(storage_path('app/public/' . $zipPath)) === TRUE) {
        $zip->extractTo($storagePath);
        $zip->close();
    } else {
        return response()->json(['error' => 'Gagal mengekstrak file ZIP'], 500);
    }


    $game = Games::create([
        'developer_id' => auth()->id(),
        'title' => $request->title,
        'slug' => Str::slug($request->title),
        'description' => $request->description,
        'thumbnail_path' => $thumbnailPath,
        'zip_path' => $zipPath,
        'extracted_path' => $extractedPath,
        'category_id' => $request->category_id,
        'status' => 'pending',
    ]);

    return response()->json($game, 201);
}
    public function update(Request $request, $id)
    {
        $game = Games::where('developer_id', auth()->id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'zip' => 'nullable|file|mimes:zip',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('thumbnail')) {
            Storage::disk('public')->delete($game->thumbnail_path);
            $game->thumbnail_path = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($request->hasFile('zip')) {
            Storage::disk('public')->delete($game->zip_path);
            $game->zip_path = $request->file('zip')->store('games/zip', 'public');
        }

        $game->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        return response()->json($game);
    }

  
    public function destroy($id)
    {
        $game = Games::where('developer_id', auth()->id())->findOrFail($id);

        Storage::disk('public')->delete($game->thumbnail_path);
        Storage::disk('public')->delete($game->zip_path);
        $game->delete();

        return response()->json(['message' => 'Game deleted successfully']);
    }
    public function increasePlayCount($slug)
    {
        $game = Games::where('slug', $slug)->firstOrFail();
        $game->increment('play_count');
        return response()->json(['message' => 'Play count updated']);
    }
    
    public function indexDeveloper(Request $request)
{
    $user = $request->user();

    $games = Games::where('developer_id', auth()->id())->latest()->get();


    return response()->json($games);
}


public function indexlistgame()
    {
        $games = Games::all();
        return response()->json([
            'data' => $games
        ]);
    }


    public function showBySlug($slug)
{
    $game = Games::where('slug', $slug)->where('status', 'published')->first();

    if (!$game) {
        return response()->json([
            'message' => 'Game tidak ditemukan atau belum dipublikasikan.'
        ], 404);
    }

    return response()->json([
        'data' => $game
    ]);
}


}
