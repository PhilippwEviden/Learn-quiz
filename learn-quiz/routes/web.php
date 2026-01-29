<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Login;


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/Login', 'pages::login')->name('login');
Route::livewire('/Signup', 'pages::signup')->name('signup');