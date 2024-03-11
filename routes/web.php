<?php

use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/register', [RegisterController::class, 'store'])->name('register');

Route::get('/login', [LoginController::class, 'index'])->name('login.index');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::prefix('game')->group(function () {
        Route::get('/', [GameController::class, 'index'])->name('game.index');
        Route::post('/answer', [GameController::class, 'answer'])->name('game.answer');
    });
});

Route::middleware(['auth', 'auth.admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/questions', [QuestionController::class, 'index'])->name('admin.questions.index');
        Route::get('/questions/create', [QuestionController::class, 'create'])->name('admin.questions.create');
        Route::post('/questions', [QuestionController::class, 'store'])->name('admin.questions.store');
        Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('admin.questions.edit');
        Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('admin.questions.update');
        Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('admin.questions.destroy');

    });
});
;
