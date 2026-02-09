<?php

use Livewire\Component;
use App\Models\BasicCard;
use App\Models\MultipleChoiceCard;

new class extends Component
{
    public $cards = [];
    public $mcCards = [];
    public string $name = '';
    public string $description = '';
    public string $type = 'multiple_choice';

    public function mount() 
    {   
        if(!auth()->user()) {
            return redirect()->intended('/login');
        }
        $this->addCard();
    }

    public function addCard() {
        $this->mcCards[] = ['question' => '', 'answer1' => '', 'answer2' => '', 'answer3' => '', 'answer4' => '', 'correct_answer' => 0];
        $this->cards[] = ['expression' => '', 'definition' => ''];
    }
    public function saveDeck() {

        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $deck = new \App\Models\Deck();
        $deck->name = $this->name;
        $deck->description = $this->description ?? '';
        $deck->save();
        if($this->type === 'flashcards'){
            foreach($this->cards as $cardData) {
                $card = BasicCard::create([
                    'expression' => $cardData['expression'],
                    'definition' => $cardData['definition'],
                ]);
                $card->card()->create(['deck_id' => $deck->id]);
            }
        }
        else {
            foreach($this->mcCards as $cardData) {
                $content = MultipleChoiceCard::create([
                    'question' => $cardData['question'],
                    'answer1' => $cardData['answer1'],
                    'answer2' => $cardData['answer2'],
                    'answer3' => $cardData['answer3'],
                    'answer4' => $cardData['answer4'],
                    'correct_answer' => (int) $cardData['correct_answer'],
                ]);

                // 2. Create the polymorphic bridge
                $content->card()->create(['deck_id' => $deck->id]);
            }
        }
        Auth::user()->decks()->syncWithoutDetaching([$deck->id]);
        return redirect()->to('/');
    }
};
?>

<flux:card class="space-y-6 max-w-l mx-auto mt-20">
    <div class="grid grid-cols-1 md:grid-cols-2 items-center">
        <div>
            <flux:heading size="lg">Create a new Deck</flux:heading>
            <flux:text class="mt-2">Organize your learning by creating a new deck.</flux:text>
        </div>
        <flux:button.group class="justify-end">
            <flux:button icon="list-bullet" title="multiple choice" wire:click="$set('type', 'multiple_choice')" :variant="$type === 'multiple_choice' ? 'primary' : 'ghost'"></flux:button>
            <flux:button icon="credit-card" title="flashcards" wire:click="$set('type', 'flashcards')" :variant="$type === 'flashcards' ? 'primary' : 'ghost'"></flux:button>
        </flux:button.group>
    </div>

    <div class="space-y-6">
        <flux:input label="Deck Name*" placeholder="Enter deck name" wire:model="name" />
        <flux:input label="Description" placeholder="Enter deck description" wire:model="description" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @if($type === 'flashcards')
            @foreach ($cards as $index => $card)
                <flux:card class="space-y-4">
                    <flux:input label="Expression" wire:model.live="cards.{{ $index }}.expression"/>
                    <flux:input label="Definition" wire:model.live="cards.{{ $index }}.definition"/>
                </flux:card>
            @endforeach
        @else
            @foreach ($mcCards as $index => $card)
                <flux:card class="space-y-4">
                    <flux:input label="Question" wire:model.live="mcCards.{{ $index }}.question"/>
                    
                    <flux:radio.group wire:model.live="mcCards.{{ $index }}.correct_answer" class="grid grid-cols-[auto_1fr] items-center gap-x-3 gap-y-2">
                        <flux:radio value="1" />
                        <flux:input placeholder="Answer 1" wire:model.live="mcCards.{{ $index }}.answer1"/>
                        
                        <flux:radio value="2" />
                        <flux:input placeholder="Answer 2" wire:model.live="mcCards.{{ $index }}.answer2"/>
                        
                        <flux:radio value="3" />
                        <flux:input placeholder="Answer 3" wire:model.live="mcCards.{{ $index }}.answer3"/>
                        
                        <flux:radio value="4" />
                        <flux:input placeholder="Answer 4" wire:model.live="mcCards.{{ $index }}.answer4"/>
                    </flux:radio.group>
                </flux:card>
            @endforeach
        @endif

        {{-- Add Card Button --}}
        <flux:button 
            variant="ghost" 
            wire:click="addCard"
            class="w-full h-full min-h-[150px] border-dashed border-2 flex flex-col items-center justify-center group"
        >
            <span class="text-4xl font-light text-zinc-400 group-hover:text-zinc-800 transition-colors">+</span>
            <span class="text-sm text-zinc-400">Add another card</span>
        </flux:button>
    </div>

    <div class="flex gap-2 justify-end">
        <flux:button variant="filled" href="/">Cancel</flux:button>
        <flux:button variant="primary" wire:click="saveDeck">Save Deck</flux:button>
    </div>
</flux:card>