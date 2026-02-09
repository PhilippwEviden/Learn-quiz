<?php

use Livewire\Component;
use App\Models\Deck;
new class extends Component
{
    public $deck;
    public $cardIndex = 0;
    public $isFlipped = false;
    public $type;
    public function mount(App\Models\Deck $deck) 
    {
        if(!auth()->user()) {
            return redirect()->intended('/login');
        }
        if(Auth::user()->decks->contains($deck->id)) {
            $this->deck = $deck;
        } else {
            return redirect()->to('/');
        }
        $this->type = $this->deck->cards->first() ? ($this->deck->cards->first()->cardable_type === 'App\Models\MultipleChoiceCard' ? 'multiple_choice' : 'flashcards') : 'multiple_choice';
        }
    public function flipCard() {
        $this->isFlipped = !$this->isFlipped;
    }
    public function nextCard() {
        if($this->cardIndex < $this->deck->cards->count() - 1) {
            $this->cardIndex++;
            $this->isFlipped = false;
        }
    }
    public function previousCard() {
        if($this->cardIndex > 0) {
            $this->cardIndex--;
            $this->isFlipped = false;
        }
    }
    
};
?>

<div>
    <div class="flex justify-between items-center mb-4">
    <flux:header class="text-2xl mb-4">{{ $this->deck->name }}</flux:header>
    <flux:button >Edit</flux:button>
    </div>
    <flux:text class="mb-6">{{ $this->deck->description }}</flux:text>

    @if($this->type === 'flashcards')
    <flux:card class="max-w-md mx-auto p-6 mt-20" wire:click="flipCard">
        <button class="size-full justify-center flex flex-col items-center mb-20 mt-20 hover:bg-transparent active:bg-transparent shadow-none border-none text-xl font-bold">
            @if(!$this->deck->cards->isEmpty())
                @if($this->isFlipped)
                    {{$this->deck->cards->get($this->cardIndex)->cardable->definition}}
                @else
                {{$this->deck->cards->get($this->cardIndex)->cardable->expression}}
                @endif
            @else
                This deck has no cards yet.
            @endif
        </button>
    </flux:card>

    <div class="justify-center items-center flex mt-6 mb-4">
        <flux:button variant="filled" wire:click="previousCard" icon="chevron-left" class="mx-4"></flux:button>
        <flux:text class="">{{ $this->cardIndex + 1 }}/{{ $this->deck->cards->count() }}</flux:text>
        <flux:button variant="filled" wire:click="nextCard" icon="chevron-right" class="mx-4"></flux:button>
    </div>
    @else
        <flux:button variant="primary" class="" color="green" href="/deck/{{ $this->deck->id }}/mc-learn">Learn</flux:button>
    @endif
    
</div>