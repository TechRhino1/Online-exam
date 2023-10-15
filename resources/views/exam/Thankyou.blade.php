@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f0f0f0;
        color: #333;
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
        font-size: 48px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .message {
        font-size: 24px;
        font-weight: normal;
        margin-bottom: 20px;
    }
</style>

<div class="container">
    <h1 class="title">Thank You</h1>
    <p class="message">Your Exam has been submitted successfully</p>
</div>
<script>
    setTimeout(function() {
        window.location.href = '/exam/results';
    }, 1000); // (1 seconds)
</script>
@endsection
