<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function takeQuiz()
    {
       return view('quiz.take-quiz');
    }
}
