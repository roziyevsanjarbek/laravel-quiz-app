<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Option;
use App\Models\Question;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Result::query()
            ->where('user_id', auth()->id())
            ->with('quiz:id,title')
            ->get();
        $data = [];
        foreach ($results as $result) {
            $question_count = Question::query()
                ->where('quiz_id', $result->quiz_id)
                ->count();
            $answers = Answer::query()
                ->where('result_id', $result->id)
                ->get();
            $correctOptionCount = Option::query()
                ->select('question_id')
                ->where('is_correct', 1)
                ->whereIn('id', $answers->pluck('option_id'))
                ->count();
            $data = [
                [
                    'score'=>(int)($correctOptionCount/$question_count * 100),
                    'result' => $result,
                    'time_taken' => Date::createFromFormat('Y-m-d H:i:s', $result->finished_at)->diff($result->started_at),
                    'status' => ($result->finished_at <= now() ? 'Completed' : 'In Progress'),
                ]
            ];
        }
        return view('dashboard.statistics', [
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Result $result)
    {
        $result->load('quiz.questions.options');

        // Get user's answers with option_id
        $userAnswers = Answer::where('result_id', $result->id)
            ->pluck('option_id')
            ->toArray();

        $data = ['quiz' => $result->quiz()->first()];
        $data['questions'] = [];

        foreach ($result->quiz->questions as $question) {
            $questionData = [
                'question' => $question->name,
                'correct_answer' => null,
                'user_answer' => null,
                'is_correct' => false
            ];

            // Find the correct answer
            $correctOption = $question->options->where('is_correct', true)->first();
            if ($correctOption) {
                $questionData['correct_answer'] = $correctOption->name;
            }

            // Find user's answer for this question
            $userOptionId = in_array($correctOption->id, $userAnswers);
            if ($userOptionId) {
                $userOption = $question->options->find($correctOption->id);
                $questionData['user_answer'] = $userOption ? $userOption->name : 'Not answered';
                $questionData['is_correct'] = ($userOption && $userOption->is_correct);
            } else {
                $questionData['user_answer'] = 'Not answered';
            }

            $data['questions'][] = $questionData;
        }
        return view('dashboard.result', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
