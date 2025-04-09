<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ScoreController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Auth\Events\Verified;
use App\Models\User;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\NotificationController;
use App\Models\Games;
use Illuminate\Notifications\Notification;

Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']); 



Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::find($id) ?? abort(404);

    abort_unless(hash_equals((string) $hash, sha1($user->getEmailForVerification())), 403);

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return Redirect::to('http://127.0.0.1:3000/email-verify/redirect?token=' . $token . '&name=' . urlencode($user->name) . '&role=' . $user->getRoleNames()->first());
})->middleware(['signed'])->name('verification.verify');



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'role' => $user->getRoleNames()->first(),
        ]);
    });

    Route::post('/logout', LogoutController::class);

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()?->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification email sent']);
    });
});


Route::get('/leaderboard/{gameId}', [LeaderboardController::class, 'show']);
Route::get('/games/{slug}', [GameController::class, 'show']);
Route::get('/games/{slug}/recommendations', [GameController::class, 'recommendations']);
Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin/dashboard', function () {
    return response()->json([
        'users' => User::role('user')->count(),
        'developers' => User::role('developer')->count(),
        'games' => Games::count(),
    ]);
});

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/developers', [AdminController::class, 'getDevelopers']);
    Route::get('/pending-games', [AdminController::class, 'getPendingGames']);
    Route::post('/approve-game/{gameId}', [AdminController::class, 'approveGame']);
    Route::post('/send-notification', [AdminController::class, 'sendNotification']);
    Route::get('/users', [AdminController::class, 'indexalluser']);
});


Route::middleware(['auth:sanctum'])->prefix('developer')->group(function () {
    Route::post('/games', [GameController::class, 'store']);
    Route::get('/games/{id}', [GameController::class, 'showDeveloperGame']); 
    Route::put('/games/{id}', [GameController::class, 'update']);
    Route::delete('/games/{id}', [GameController::class, 'destroy']);
    Route::get('/games', [GameController::class, 'indexDeveloper']);
    Route::get('/notifications', [NotificationController::class, 'index']);
});


Route::middleware('auth:sanctum')->post('/games/{slug}/play', [GameController::class, 'increasePlayCount']);
Route::get('/games', [GameController::class, 'indexlistgame']);
Route::get('/games/{slug}', [GameController::class, 'show']);
Route::post('/scores', [ScoreController::class, 'store']);

Route::get('/games/{slug}', [GameController::class, 'showBySlug']);
