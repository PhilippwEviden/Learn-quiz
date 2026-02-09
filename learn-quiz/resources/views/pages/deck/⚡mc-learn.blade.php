<?php

use Livewire\Component;
use App\Models\Deck;


new class extends Component
{
    public $deck;
    public $cardIndex = 0;
    public $selectedAnswer = 0;
    public $resultShown = false;
    public function mount(Deck $deck) 
    {
        if(!auth()->user()) {
            return redirect()->intended('/login');
        }
        $this->deck = $deck;
    }
    public function check() {
        $this->resultShown = true;
    }
    public function nextCard() {
        $this->resultShown = false;
        if($this->cardIndex < $this->deck->cards->count() - 1) {
            $this->cardIndex++;
            $this->selectedAnswer = 0;
        }
    }
};
?>

<div class="">
    <flux:heading class="text-2xl mb-4">{{ $this->deck->name }}</flux:heading>
    <flux:text class="mb-6">{{ $this->deck->description }}</flux:text>
    <div>    
        <flux:card>

            <flux:heading>{{ $this->deck->cards[$this->cardIndex]->cardable->question }}</flux:heading>

            <flux:radio.group class="space-y-4 mt-6 w-full" wire:model="selectedAnswer">
                @php 
                    $cardable = $this->deck->cards[$this->cardIndex]->cardable;
                    $correct = (int) $cardable->correct_answer;
                @endphp

                @foreach([1, 2, 3, 4] as $idx)
                    @php
                        $isCorrect = $resultShown && $idx === $correct;
                        $isWrong = $resultShown && (int)$selectedAnswer === $idx && $idx !== $correct;
                        
                        $bgColor = '';
                        if ($isCorrect) $bgColor = 'bg-green-100 border-green-500 dark:bg-green-900/30';
                        if ($isWrong) $bgColor = 'bg-red-100 border-red-500 dark:bg-red-900/30';
                    @endphp

                    <div class="p-2 rounded-lg transition-colors {{ $bgColor }}">
                        <flux:radio 
                            value="{{ $idx }}" 
                            label="{{ $cardable->{'answer' . $idx} }}" 
                            :disabled="$resultShown" 
                        />
                    </div>
                @endforeach
            </flux:radio.group>
           @if(!$resultShown)
                <flux:button variant="primary" color="green" class="w-full mt-6" wire:click="check()">Submit</flux:button>
            @else
                @if($this->cardIndex < $this->deck->cards->count() - 1)
                <flux:button variant="filled" class="w-full mt-6" wire:click="nextCard">Next Card</flux:button>
                @else
                    <flux:button variant="ghost" class="w-full mt-6 cursor-not-allowed" disabled>Finished</flux:button>
                @endif
            @endif
        </flux:card>
    </div>
</div>