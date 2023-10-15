@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f0f0f0; /* Updated background color */
        color: #333; /* Updated text color */
        font-family: 'Nunito', sans-serif;
        height: 100vh;
        margin: 0;
        align-items: center;
        justify-content: center;
    }
    .container {
        text-align: center;
    }
    .title {
        font-size: 48px; /* Increased font size */
        font-weight: bold;
        margin-bottom: 20px;
    }
    .score {
        font-size: 36px; /* Increased font size */
        font-weight: bold;
        margin-bottom: 20px;
    }
    .btn-primary {
        font-size: 24px; /* Increased font size */
    }
</style>

<div class="container">
    <h1 class="title">Exam Results</h1>
    <p class="score">Your Score: {{ $score }} /10</p>
    <a href="/thankyou" class="btn btn-primary">Finish</a>
</div>
@endsection
