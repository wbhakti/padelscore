<?php

use App\Http\Controllers\TournamentController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Proteksi Route Turnamen dengan Middleware 'auth'
Route::middleware('auth')->group(function () {
    
    // Halaman Utama (Langsung diarahkan ke bikin room saat akses web utama)
    Route::get('/', [TournamentController::class, 'history'])->name('tournament.history');
	Route::get('/tournament/room', [TournamentController::class, 'createRoom'])->name('tournament.create-room');
    Route::post('/tournament/generate-room', [TournamentController::class, 'storeRoom'])->name('tournament.store-room');
    
    // Kelola Turnamen & Match (Menggunakan URL singkat '/t/{code}' agar lebih keren)
    Route::get('/t/{code}', [TournamentController::class, 'index'])->name('tournament.index');
    Route::post('/t/{code}/start', [TournamentController::class, 'start'])->name('tournament.start');
    Route::put('/t/{code}/match/{matchId}/score', [TournamentController::class, 'updateScore'])->name('match.update-score');
    
    // Riwayat & Fitur Hapus Turnamen
    Route::get('/tournament/history', [TournamentController::class, 'history'])->name('tournament.history');
    Route::delete('/tournament/{code}/delete', [TournamentController::class, 'destroy'])->name('tournament.destroy');
    
});
