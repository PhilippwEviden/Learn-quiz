<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MultipleChoiceCard extends Model
{
    protected $fillable = [
        'question',
        'answer1',
        'answer2',
        'answer3',
        'answer4',
        'correct_answer',
    ];
    public function card()
    {
        return $this->morphOne(Card::class, 'cardable');
    }
}
