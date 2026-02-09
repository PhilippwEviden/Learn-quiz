<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    protected $fillable = [
        'deck_id',
        'cardable_id',
        'cardable_type'
    ];
    public function cardable()
    {
        // This will automatically resolve to a MultipleChoiceCard or BasicCard instance
        return $this->morphTo();
    }
    public function deck(): BelongsTo
    {
        return $this->belongsTo(Deck::class);
    }
}
