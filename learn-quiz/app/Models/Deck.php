<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deck extends Model
{
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
