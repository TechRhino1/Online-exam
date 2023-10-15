@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
    }

    .question-container {
        margin: 20px;
        background-color: #f4f4f4;
        padding: 20px;
        border-radius: 5px;
    }

    .question {
        margin-bottom: 20px;
    }

    .answer-options label {
        cursor: pointer;
        margin-left: 5px;
    }

    #progress-summary {
        display: flex;
        align-items: center;
        margin-top: 20px;
    }

    .progress {
        width: 80%;
        height: 10px;
        background-color: #ccc;
        border-radius: 5px;
        overflow: hidden;
    }

    .progress div {
        height: 100%;
        transition: background-color 0.3s ease-in-out;
    }

    .answered {
        background-color: green;
    }

    .not-answered {
        background-color: red;
    }

    .marked {
        background-color: blue;
    }

    .not-visited {
        background-color: gray;
    }

    .next-question,
    .next-question:hover {
        background-color: #007BFF;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .prev-selected-answer {
        font-weight: bold;
        color: red;
        /* Adjust color as needed */
    }

    .save-button {
        background-color: #007BFF;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .card-right {
        position: fixed;
        top: 81.5px;
        right: 0;
        width: 20%;
        height: 100%;
        background-color: #f4f4f4;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        padding: 20px;
        overflow-y: scroll;
    }

    .card-body {
        padding: 0;
    }

    .card-title {
        font-size: 1.2rem;
        margin-bottom: 20px;
        text-align: center;
    }

    .card-subtitle {
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .card-subtitle h1 {
        font-size: 1.5rem;
        margin-left: 10px;
    }

    hr {
        margin-top: 20px;
        margin-bottom: 20px;
    }

    #timer {
        font-size: 2rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 10px;
    }

    #time-taken {
        font-size: 2rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 10px;
    }

    .card-text span {
        font-weight: bold;
    }

    .container-card {
        background-color: #f4f4f4;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        margin: 20px;
    }

    /* Button styles */
    .next-question,
    .prev-question,
    .save-button {
        background-color: #007BFF;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 10px;
    }

    .options-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        /* You can adjust the number of columns as needed */
        gap: 10px;
        /* Adjust the gap between items as needed */
    }

    .option {
        display: none;
        /* Hide the paragraph elements */
    }

    .next-question:hover,
    .prev-question:hover,
    .save-button:hover {
        background-color: #0056b3;
    }

    .mark-unmark-button,
    .review-button {
        background-color: #007BFF;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 10px;
    }

    .mark-unmark-button:hover,
    .review-button:hover {
        background-color: #0056b3;
    }
</style><!-- Container Card -->
<div class="container-card">
    <form id="formexam" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="question-container">
            @foreach($questions as $key => $question)
            <div class="question" data-question-id="{{ $question->id }}" @if ($key !==0) style="display: none;" @endif>
                <p>{{ $key + 1 }} ) {{ $question->question_test }}</p>
                <input type="hidden" name="question_id" value="{{ $question->id }}">
                <div class="answer-options">
                    @php
                    $prevSelectedAnswer = session('selectedAnswers')[$question->id] ?? null;
                    @endphp
                    <div class="options-grid">
                        <div>
                            <input type="radio" name="answer" value="A" id="optionA" @if($prevSelectedAnswer=='A' ) checked @endif>
                            <label for="optionA" class="{{ $prevSelectedAnswer == 'A' ? 'prev-selected-answer' : '' }}">{{ $question->A }}</label>
                        </div>
                        <div>
                            <input type="radio" name="answer" value="B" id="optionB" @if($prevSelectedAnswer=='B' ) checked @endif>
                            <label for="optionB" class="{{ $prevSelectedAnswer == 'B' ? 'prev-selected-answer' : '' }}">{{ $question->B }}</label>
                        </div>
                        <div>
                            <input type="radio" name="answer" value="C" id="optionC" @if($prevSelectedAnswer=='C' ) checked @endif>
                            <label for="optionC" class="{{ $prevSelectedAnswer == 'C' ? 'prev-selected-answer' : '' }}">{{ $question->C }}</label>
                        </div>
                        <div>
                            <input type="radio" name="answer" value="D" id="optionD" @if($prevSelectedAnswer=='D' ) checked @endif>
                            <label for="optionD" class="{{ $prevSelectedAnswer == 'D' ? 'prev-selected-answer' : '' }}">{{ $question->D }}</label>
                        </div>
                    </div>
                </div>
                @if ($key !== 0)
                <button type="button" class="prev-question">Previous Question</button>
                @endif
                @if ($key === count($questions) - 1)
                <button type="button" class="save-button">Finish</button>
                @else
                <button type="button" class="mark-unmark-button">Mark/Unmark</button>
                <button type="button" class="next-question">Next Question</button>
                @endif

            </div>
            @endforeach
        </div>
    </form>
</div>

<!-- create a div verticle on the right side in a card design -->
<div class="card card-right">
    <div class="card-body">
        <h4 class="card-title" style="text-align: center; margin-bottom: 20px;">Exam Summary</h4>
        <hr>
        <h2 class="card-subtitle mb-2 text-muted">Time Remaining <h1 id="timer" style="display: inline-block;">10:00</h1>
        </h2>
        <h2 class="card-subtitle mb-2 text-muted">Total Time <h1 id="time-taken" style="display: inline-block;">10:00</h1>
        </h2>
        <hr>
        <!-- Total Questions -->
        <p class="card-text">Total Questions: {{ count($questions) }}</p>

        <p class="card-text">Attempted Questions:
        <div class="answered"><span id="attempted-questions">0</span></div>
        </p>

        <p class="card-text">Not Attempted Questions:
        <div class="not-answered"> <span id="not-attempted-questions">0</span></div>
        </p>

        <p class="card-text">Marked Questions:
        <div class="marked"><span id="marked-questions">0</span></div>
        </p>

        <p class="card-text">Not Visited Questions:
        <div class="not-visited"><span id="not-visited-questions">0</span></div>
        </p>
        <hr>

    </div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    var timer = 600;
    var currentQuestionIndex = 0;
    var selectedAnswers = {};

    function showQuestion(index) {
        $('.question').hide();
        var $currentQuestion = $('.question').eq(index);
        $currentQuestion.show();

        // Get the selected answer for the current question
        var currentQuestionID = $currentQuestion.find('input[name="question_id"]').val();
        var selectedAnswer = selectedAnswers[currentQuestionID];

        // Check if a selected answer exists and set the corresponding radio button as checked
        if (selectedAnswer) {
            $('input[name="answer"][value="' + selectedAnswer + '"]').prop('checked', true);
        }
    }

    showQuestion(currentQuestionIndex);

    var interval = setInterval(function() {
        timer--;
        var min = Math.floor(timer / 60);
        var sec = timer % 60;
        var timerDisplay = (min < 10 ? '0' + min : min) + ':' + (sec < 10 ? '0' + sec : sec);
        document.getElementById('timer').innerHTML = timerDisplay;
        if (timer === 0) {
            clearInterval(interval);
            document.getElementById('timer').innerHTML = 'Time is up!';
            window.location.href = '/exam/thankyou';
        }
    }, 1000);

    $(document).ready(function() {
        var isSubmitting = false;

        $('.next-question').on('click', function() {
            if ($('input[type=radio]:checked').length === 0 || isSubmitting) {
                alert('Please select an option or wait for the previous submission to complete.');
            } else {
                var currentQuestion = $('.question:visible');
                var nextQuestion = currentQuestion.next('.question');
                $('#subans').prop('disabled', true);
                var currentQuestionID = currentQuestion.find('input[name="question_id"]').val();

                selectedAnswers[currentQuestionID] = $('input[name="answer"]:checked').val();

                $.ajax({
                    type: 'POST',
                    url: '/exam/submit',
                    data: $('#formexam').serialize() + "&question_id=" + currentQuestionID,
                    success: function(response) {
                        $('#formexam')[0].reset();

                        if (nextQuestion.length) {
                            currentQuestion.hide();
                            nextQuestion.show();
                            currentQuestionIndex++;
                        } else {
                            alert('No more questions');
                        }
                    },
                    complete: function() {
                        $('#subans').prop('disabled', false);
                        isSubmitting = false;
                    }
                });
            }
        });

        $('.prev-question').on('click', function() {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                showQuestion(currentQuestionIndex);
            }
        });

        // Handle the "Save" button click for the last question
        $('.save-button').on('click', function() {
            if ($('input[type=radio]:checked').length === 0 || isSubmitting) {
                alert('Please select an option or wait for the previous submission to complete.');
            } else {
                var currentQuestion = $('.question:visible');
                var currentQuestionID = currentQuestion.find('input[name="question_id"]').val();

                selectedAnswers[currentQuestionID] = $('input[name="answer"]:checked').val();

                $.ajax({
                    type: 'POST',
                    url: '/exam/submit',
                    data: $('#formexam').serialize() + "&question_id=" + currentQuestionID,
                    success: function(response) {
                        $('#formexam')[0].reset();
                        window.location.href = '/exam/thankyou';
                    },
                    complete: function() {
                        isSubmitting = false;
                    }
                });
            }
        });
    });
    $(document).ready(function() {
        function updateSummary() {
            $.ajax({
                type: 'GET',
                url: '/exam/show',
                success: function(response) {
                    $('#attempted-questions').text(response.attemptedQuestions);
                    $('#not-attempted-questions').text(response.notAttemptedQuestions);
                    $('#marked-questions').text(response.markedQuestions);
                    $('#not-visited-questions').text(response.notVisitedQuestions);
                }
            });
        }
        updateSummary();

        $('input[type="radio"]').on('change', function() {
            updateSummary();
        });
    });
    $(document).ready(function() {
        if (window.performance) {
            if (performance.navigation.type == 1) {
                show = confirm("You are about to leave this page. Are you sure?");
                window.location.href = '/exam/thankyou';

            }
        }
    });
</script>
@endsection
