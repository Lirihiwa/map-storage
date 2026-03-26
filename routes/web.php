<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BeatmapController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BeatmapController as AdminBeatmapController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('welcome');

Route::get('/admin', function () {
	return 'Добро пожаловать, администратор!';
})->middleware(['auth', 'role:admin']);

Route::get('/moderator', function () {
	return 'Добро пожаловать, модератор!';
})->middleware(['auth', 'role:moderator']);

Route::middleware('auth')->group(function () {
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
	Route::get('/beatmaps/upload', [BeatmapController::class, 'create'])->name('beatmaps.upload');
	Route::post('/beatmaps/upload', [BeatmapController::class, 'store'])->name('beatmaps.store');
});

Route::middleware(['auth', 'role:admin,moderator'])->prefix('panel')->group(function () {
	Route::get('/beatmaps', [AdminBeatmapController::class, 'index'])->name('admin.beatmaps.index');
	Route::patch('/beatmaps/{beatmapSet}/status', [AdminBeatmapController::class, 'updateStatus'])->name('admin.beatmaps.status');
	Route::delete('/beatmaps/{beatmapSet}', [AdminBeatmapController::class, 'destroy'])->name('admin.beatmaps.destroy');

	Route::middleware('role:admin')->group(function () {
		Route::get('/dashboard', [AdminDashboardController::class,'index'])->name('admin.dashboard');

		Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
		Route::patch('/users/{user}/roles', [AdminUserController::class, 'updateRoles'])->name('admin.users.roles');
		Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
	});
});

Route::get('/beatmaps', [BeatmapController::class, 'index'])->name('beatmaps.index');
Route::get('/beatmaps/{beatmapSet}/download', [BeatmapController::class, 'download'])->name('beatmaps.download');
Route::get('/beatmaps/{beatmapSet}', [BeatmapController::class, 'show'])->name('beatmaps.show');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
	Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

require __DIR__ . '/auth.php';
