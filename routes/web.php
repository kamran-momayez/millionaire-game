<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GameController;
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

Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/register', [RegisterController::class, 'store'])->name('register');

Route::prefix('game')->group(function () {
    Route::get('/', [GameController::class, 'index'])->name('game.index');
    Route::post('/answer', [GameController::class, 'answer'])->name('game.answer');
});
