<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'multiple_choice_card_id',
        'answer_text',
        'is_correct',
    ];

    public function multipleChoiceCard()
    {
        return $this->belongsTo(MultipleChoiceCard::class);
    }
}