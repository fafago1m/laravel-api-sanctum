<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Mengambil daftar pengguna untuk admin
    public function index()
    {
        // Ambil semua pengguna
        $users = User::all();

        // Kembalikan data pengguna dalam format JSON
        return response()->json($users);
    }
}
