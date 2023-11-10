<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ArticleController::class, 'index'])
    ->name('root');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::resource('audios', AudioController::class)
//     ->only(['create', 'store', 'edit', 'update', 'destroy'])
//     ->middleware('auth');

// Route::resource('audios', AudioController::class)
//     ->only(['show', 'index']);
Route::resource('audios', AudioController::class);

Route::resource('articles', ArticleController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->middleware('auth');

Route::resource('articles', ArticleController::class)
    ->only(['show', 'index']);

require __DIR__ . '/auth.php';
