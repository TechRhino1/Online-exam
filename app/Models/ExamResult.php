<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;
    protected $table = 'exam_result';
        protected $fillable = [
            'user_id',
            'question_id',
            'selected_option',
            'score',
        ];

        protected $casts = [
            'options' => 'array',
        ];
}
