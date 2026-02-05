<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Actions\Logout;

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';

Route::get('/logout', Logout::class)->name('logout');

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/login', 'pages::login')->name('login');
Route::livewire('/signup', 'pages::signup')->name('signup');
Route::livewire('/deck/create', 'pages::deck.create')->name('deck.create');
Route::livewire('/deck/{deck}', 'pages::deck.view')->name('deck.view');