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
        $this->mcCards[] = [
        'question' => '', 
        'answers' => [
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
        ]
    ];
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
                $mcCard = MultipleChoiceCard::create([
                    'question' => $cardData['question'],
                ]);
                foreach($cardData['answers'] as $answerData) {
                    $mcCard->answers()->create([
                        'answer_text' => $answerData['text'],
                        'is_correct' => $answerData['is_correct']
                    ]);
                }
                // 2. Create the polymorphic bridge
                $mcCard->card()->create(['deck_id' => $deck->id]);
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
                    <div class="flex justify-between items-center">
                        <flux:button variant="ghost" icon="trash" wire:click="removeCard({{ $index }})" />
                    </div>
                    <flux:input label="Question" wire:model="mcCards.{{ $index }}.question" />
                    <div class="space-y-3">
                        <flux:label>Answers</flux:label>
                        @foreach($mcCards[$index]['answers'] as $answerIndex => $answer)
                            <div class="grid grid-cols-[auto_1fr] items-center gap-x-3">
                                <flux:checkbox 
                                    wire:model="mcCards.{{ $index }}.answers.{{ $answerIndex }}.is_correct" 
                                />
                                <flux:input 
                                    placeholder="Answer option..." 
                                    wire:model="mcCards.{{ $index }}.answers.{{ $answerIndex }}.text" 
                                />
                            </div>
                        @endforeach
                    </div>
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