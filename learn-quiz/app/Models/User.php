<?php

namespace App\Models;

// Change the import to the Foundation class
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Deck;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Hidden attributes (e.g. password) that shouldn't be visible in arrays/JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Ensure the password is always hashed (optional but recommended for newer Laravel)
     */
    protected $casts = [
        'password' => 'hashed',
    ];
    public function decks(): BelongsToMany 
    {
        return $this->belongsToMany(Deck::class);
    }
}