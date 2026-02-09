<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasicCard extends Model
{
    protected $fillable = [
        'expression',
        'definition',
    ];
    public function card()
    {
        return $this->morphOne(Card::class, 'cardable');
    }
}
