<?php

use Livewire\Component;
use App\Models\Deck;


new class extends Component
{
    public $deck;
    public $cardIndex = 0;
    public $selectedAnswers = [];
    public $resultShown = false;
    public $correctAnswered = 0;
    public function mount(Deck $deck) 
    {
        $this->deck = $deck;
        if(!auth()->user()) {
            return redirect()->intended('/login');
        }
        $this->deck->cards = $this->deck->cards->shuffle();
    }
    public function check() {
        $this->resultShown = true;
        if($this->selectedAnswers == $this->deck->cards[$this->cardIndex]->cardable->answers->where('is_correct', true)->pluck('id')->toArray()) {
            $this->correctAnswered++;
        }
    }
    public function nextCard() {
        $this->resultShown = false;
        if($this->cardIndex < $this->deck->cards->count() - 1) {
            $this->cardIndex++;
            $this->selectedAnswers = [];
        }
    }
};
?>

<div class="">
    <flux:heading class="text-2xl mb-4">{{ $this->deck->name }}</flux:heading>
    <flux:text class="mb-6">{{ $this->deck->description }}</flux:text>
    
    <div>    
        <flux:card>
            @php 
                $currentCard = $this->deck->cards[$this->cardIndex];
                $cardable = $currentCard->cardable;
                $isSingleChoice = $cardable->answers->where('is_correct', true)->count() === 1;
            @endphp
            <flux:heading>{{ $cardable->question }}</flux:heading>
            <div class="space-y-4 mt-6 w-full">
                @if($isSingleChoice)
                    <flux:radio.group wire:model="selectedAnswers.0" :disabled="$resultShown">
                        @foreach($cardable->answers as $answer)
                            @php
                                $isSelected = in_array($answer->id, (array)$selectedAnswers);
                                $isCorrect = $answer->is_correct;
                                $bgColor = $resultShown ? ($isCorrect ? 'bg-green-100 border border-green-500 dark:bg-green-900/30' : ($isSelected ? 'bg-red-100 border border-red-500 dark:bg-red-900/30' : '')) : '';
                            @endphp

                            <div class="p-3 rounded-lg transition-colors {{ $bgColor }}">
                                <flux:radio value="{{ $answer->id }}" label="{{ $answer->answer_text }}" />
                            </div>
                        @endforeach
                    </flux:radio.group>
                @else
                    @foreach($cardable->answers as $answer)
                        @php
                            $isSelected = in_array($answer->id, $selectedAnswers);
                            $isCorrect = $answer->is_correct;
                            
                            $bgColor = $resultShown ? ($isCorrect ? 'bg-green-100 border border-green-500 dark:bg-green-900/30' : ($isSelected ? 'bg-red-100 border border-red-500 dark:bg-red-900/30' : '')) : '';
                        @endphp

                        <div class="p-3 rounded-lg transition-colors {{ $bgColor }}">
                            <flux:checkbox 
                                wire:model="selectedAnswers" 
                                value="{{ $answer->id }}" 
                                label="{{ $answer->answer_text }}" 
                                :disabled="$resultShown" 
                            />
                        </div>
                    @endforeach
                @endif
            </div>
            @if(!$resultShown)
                <flux:button variant="primary" color="green" class="w-full mt-6" wire:click="check">Submit</flux:button>
            @else
                @if($this->cardIndex < $this->deck->cards->count() - 1)
                    <flux:button variant="filled" class="w-full mt-6" wire:click="nextCard">Next Card</flux:button>
                @else
                    <flux:button variant="ghost" class="w-full mt-6" href="/deck/{{ $this->deck->id }}">{{$this->correctAnswered}}/{{$this->deck->cards->count()}} Correct Answers! Back to Decks</flux:button>
                @endif
            @endif
        </flux:card>
    </div>
</div>