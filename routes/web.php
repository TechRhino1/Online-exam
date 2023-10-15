<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Models\Question;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/', [QuestionController::class, 'index'])->name('exam.index');
    Route::post('/exam/submit', [QuestionController::class, 'store'])->name('exam.submit');
    Route::get('/exam/results', [QuestionController::class, 'result'])->name('exam.results');
    Route::post('/exam/next', [QuestionController::class, 'loadNextQuestion'])->name('exam.next');
    //show
    Route::post('/exam/show', [QuestionController::class, 'show'])->name('exam.show');
    Route::get('/exam/thankyou', [QuestionController::class, 'thankyou'])->name('exam.thankyou');
});

require __DIR__.'/auth.php';
