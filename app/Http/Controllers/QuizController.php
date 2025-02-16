<?php

namespace App\Http\Controllers;


use App\Models\Answer;
use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quiz = Quiz::withCount('questions')
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('dashboard.my-quizzes', [
            'quizzes' => $quiz,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.create-quiz');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'timeLimit' => 'required|integer',
            'questions' => 'required|array',
        ]);

        $quiz = Quiz::create([
            'user_id' => auth()->id(),
            'title' => $validator['title'],
            'description' => $validator['description'],
            'time_limit' => $validator['timeLimit'],
            'slug' => Str::slug(strtotime('now') . '/' . $validator['title']),
        ]);

        foreach ($validator['questions'] as $question) {
            $questionItem = $quiz->questions()->create([
                'name' => $question['quiz'],
            ]);
            foreach ($question['options'] as $optionKey => $option) {
                $questionItem->options()->create([
                    'name' => $option,
                    'is_correct' => $question['correct'] == $optionKey ? 1 : 0,
                ]);
            }
        }
        return to_route('quizzes', [$quiz]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $quiz = Quiz::where('slug', $slug)->first();
        $result = Result::query()
            ->where('quiz_id', $quiz->id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$result) {
            return view('quiz.show-quiz', [
                'quiz' => $quiz,
            ]);
        }
        return 'Ey !';

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        return view('dashboard.edit-quiz', [
            'quiz' => $quiz->load('questions.options'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validator = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'timeLimit' => 'required|integer',
            'questions' => 'required|array',
        ]);

        $quiz->update([
            'title' => $validator['title'],
            'description' => $validator['description'],
            'time_limit' => $validator['timeLimit'],
            'slug' => Str::slug(strtotime('now') . '/' . $validator['title']),
        ]);

        $quiz->questions()->delete();

        foreach ($validator['questions'] as $question) {
            $questionItem = $quiz->questions()->create([
                'name' => $question['quiz'],
            ]);
            foreach ($question['options'] as $optionKey => $option) {
                $questionItem->options()->create([
                    'name' => $option,
                    'is_correct' => $question['correct'] == $optionKey ? 1 : 0,
                ]);
            }

        }
        return to_route('quizzes');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return to_route('quizzes');
    }

    public function startQuiz(string $slug)
    {
        $quiz = Quiz::where('slug', $slug)->with('questions.options')->first();
        $result = Result::create([
            'quiz_id' => $quiz->id,
            'user_id' => auth()->id(),
            'started_at' => now(),
            'finished_at' => date('Y-m-d H:i:s', strtotime('+' . $quiz->time_limit . ' minutes')),
        ]);
        return view('quiz.start-quiz', [
            'quiz' => $quiz->load('questions.options'),
        ]);

    }

    public function takeQuiz(string $slug, Request $request)
    {
        $validator = $request->validate([
            'answer' => 'required|integer|exists:options,id',
        ]);

        $user_id = auth()->id();
        $quiz = Quiz::where('slug', $slug)->first();

        $result = Result::where('quiz_id', $quiz->id)
            ->where('user_id', $user_id)
            ->first();

        Answer::create([
            'result_id' => $result->id,
            'option_id' => $validator['answer'],
        ]);

        if ($result->finished_at <= now()) {
            return 'Seni vaqting tugagan';
        }

        $answers = Answer::query()
            ->where('result_id', $result->id)
            ->get();

        $options = Option::query()
            ->select('question_id')
            ->whereIn('id', $answers->pluck('option_id'))
            ->get();

        $questions = Question::query()
            ->where('quiz_id', $quiz->id)
            ->whereNotIn('id', $options->pluck('question_id'))
            ->with('options')
            ->get();

        return view('quiz.take-quiz', [
            'quiz' => $quiz,
            'questions' => $questions,
        ]);


    }

}
