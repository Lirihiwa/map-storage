<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BeatmapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BeatmapController::class, 'index'])->name('home');

Route::get('/admin', function () {
	return 'Добро пожаловать, администратор!';
})->middleware(['auth', 'role:admin']);

Route::get('/moderator', function () {
	return 'Добро пожаловать, модератор!';
})->middleware(['auth', 'role:moderator']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
	Route::get('/beatmaps/upload', [BeatmapController::class, 'create'])->name('beatmaps.upload');
	Route::post('/beatmaps/upload', [BeatmapController::class, 'store'])->name('beatmaps.store');
});

require __DIR__.'/auth.php';
