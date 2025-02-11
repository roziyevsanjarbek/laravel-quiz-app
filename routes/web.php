<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\DashboardController;

Route::get('/', [HomeController::class, 'index'])->name('welcome');

Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::get('/take-quiz', [QuizController::class, 'takeQuiz'])->middleware('auth')->name('take-quiz');

//Dashboard
Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'home'])->name('dashboard');
    Route::get('/my-quizzes', [DashboardController::class, 'quizzes'])->name('quizzes');
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');

    Route::get('/create-quiz', [QuizController::class, 'create'])->name('create-quiz');
    Route::post('/create-quiz', [QuizController::class, 'store'])->name('create-quiz');





});
//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
