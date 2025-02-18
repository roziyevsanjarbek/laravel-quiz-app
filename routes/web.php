<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\DashboardController;

Route::get('/', [HomeController::class, 'index'])->name('welcome');

Route::get('/about', [HomeController::class, 'about'])->name('about');


//Dashboard

Route::middleware('auth')->group(function () {
    Route::prefix('dashboard')->middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'home'])->name('dashboard');
        Route::get('/my-quizzes', [QuizController::class, 'index'])->name('quizzes');
        Route::get('/statistics', [ResultController::class, 'index'])->name('statistics');
        Route::get('/results/{result}', [ResultController::class, 'show'])->name('my-results');


        Route::get('/create-quiz', [QuizController::class, 'create'])->name('create-quiz');
        Route::post('/create-quiz', [QuizController::class, 'store'])->name('store-quiz');

        Route::get('/my-quizzes/{quiz}', [QuizController::class, 'edit'])->name('my-quizzes');
        Route::post('/my-quizzes/{quiz}/update', [QuizController::class, 'update'])->name('update-quiz');

        Route::get('/delete-quiz/{quiz}', [QuizController::class, 'destroy'])->name('delete-quiz');

    });


    Route::get('show-quiz/{slug}', [QuizController::class, 'show'])->name('show-quiz');
    Route::post('start-quiz/{slug}', [QuizController::class, 'startQuiz'])->name('start-quiz');
    Route::post('take-quiz/{slug}', [QuizController::class, 'takeQuiz'])->name('take-quiz');
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
