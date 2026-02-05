<?php

use Livewire\Component;

new class extends Component
{
    public $cards = [];
    public function mount() 
    {
        if(!auth()->user()) {
            return redirect()->intended('/login');
        }
        $this->cards = Auth::user()->decks;
    }
};
?>

<div>
    <flux:card class="space-y-6 max-w-md mx-auto mt-20">
        <flux:button variant="primary" class="w-full bg-blue-500 hover:bg-blue-600 text-white "  href="/deck/create">
            Create new Folder
        </flux:button>
    </flux:card>

    <flux:card class="flex flex-col max-w-md mx-auto mt-10">
        <span>
            Your decks    
        </span>    
    <div class="flex flex-col  mt-4">
        @foreach($this->cards as $card)
        <a href="/deck/{{ $card->id }}">
            <flux:card class="mb-2" href="/deck/{{ $card->id }}">
                <flux:heading class="text-lg">{{ $card->name }}</flux:heading>
                <flux:text> {{$card->description}}</flux:text>
            </flux:card>
        </a>
         @endforeach
    </div>
    
    </flux:card>
</div>