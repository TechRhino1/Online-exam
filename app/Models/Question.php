<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $table = 'question';
        protected $fillable = [
            'question_test',
            'A',
            'B',
            'C',
            'D',
            'answer',
        ];

        protected $casts = [
            'options' => 'array',
        ];
}
