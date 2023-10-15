<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\ExamResult;
use App\Models\userAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve selected answers from the session or initialize an empty array
        $selectedAnswers = Session::get('selectedAnswers', []);

        // Check if questions are already in the session; if not, fetch and store them
        if (!Session::has('questions')) {
            $questions = Question::inRandomOrder()->take(10)->get();
            Session::put('questions', $questions);
            $currentQuestion = $questions->first();
        } else {
            $questions = Session::get('questions');
            $currentQuestion = $questions->first();
        }

        return view('exam.index', compact('questions', 'currentQuestion', 'selectedAnswers'));
    }

    public function loadNextQuestion(Request $request)
{
    if (!session()->has('questions')) {
        return redirect()->route('exam.index');
    }else{
    $currentQuestionId = $request->input('currentQuestionId');
    //get the questions from session
    $questions = session()->get('questions');
   // $selectedAnswers = session()->get('selectedAnswers');
    // Find the current question
   // $currentQuestion = $questions->where('id', $currentQuestionId)->first();

    $nextQuestion = $questions->where('id', '>', $currentQuestionId)->sortBy('id')->first();
    }

    if ($nextQuestion) {
        return response()->json(['question' => $nextQuestion]);
    } else {
        return response()->json(['message' => 'please click on submit button to see the result']);
    }
    //return response()->json(['currentQuestion' => $currentQuestion, 'selectedAnswer' => $selectedAnswers[$currentQuestionId], 'nextQuestion' => $nextQuestion]);
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
        $userAnswers = $request->input('answer');

        // Store selected answers in the session
        $selectedAnswers = Session::get('selectedAnswers', []);
        $selectedAnswers[$request->input('question_id')] = $userAnswers;
        Session::put('selectedAnswers', $selectedAnswers);

        // Continue with the existing code to save the answer to the database
        $score = $this->calculateScore($userAnswers, $request->input('question_id'));
        $userAnswer = new UserAnswer();
        $userAnswer->user_id = auth()->user()->id;
        $userAnswer->question_id = $request->input('question_id');
        $userAnswer->selected_option = $userAnswers;
        $userAnswer->answer = $request->input('answer');
        $userAnswer->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        // Retrieve attempted questions for the user
        $userAnswers = UserAnswer::where('user_id', auth()->user()->id)->get();

        // Retrieve not-attempted questions for the user
        $questions = Question::whereNotIn('id', $userAnswers->pluck('question_id'))->get();

        // Retrieve marked questions
        $markedQuestions = UserAnswer::where('user_id', auth()->user()->id)->where('selected_option', '!=', null)->get();

        // Retrieve the user's score
        $score = ExamResult::where('user_id', auth()->user()->id)->get('score');

        // Calculate the counts
        $attemptedQuestionsCount = count($userAnswers);
        $notAttemptedQuestionsCount = count($questions);
        $markedQuestionsCount = count($markedQuestions);
        $notVisitedQuestionsCount = $question->count() - ($attemptedQuestionsCount + $notAttemptedQuestionsCount);

        return response()->json([
            'attemptedQuestions' => $attemptedQuestionsCount,
            'notAttemptedQuestions' => $notAttemptedQuestionsCount,
            'markedQuestions' => $markedQuestionsCount,
            'notVisitedQuestions' => $notVisitedQuestionsCount,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        //
    }
    // Calculate the score
    private function calculateScore($userAnswers, $questionId)
    {
        $question = Question::find($questionId);
        $result = new ExamResult();
        $scoredata = ExamResult::where('user_id', auth()->user()->id)->get( 'score');
        $result->user_id = auth()->user()->id;
        if ($question->answer == $userAnswers) {
            if ($scoredata->isEmpty()) {
                $score = 1;
                $result->score = $score;
                $result->save();
            } else {
                $score = $scoredata[0]->score + 1;
                 ExamResult::where('user_id', auth()->user()->id)->update(['score' => $score]);
            }
            }elseif ($scoredata->isEmpty()) {
                $score = 0;
                $result->score = $score;
                $result->save();}
}
    public function result()
    { $result = ExamResult::where('user_id', auth()->user()->id)->latest()->first();
        $score = $result->score;
        return view('exam.results', compact('score'));
    }
    public function thankyou()
    {
        return view('exam.Thankyou');
    }
}
