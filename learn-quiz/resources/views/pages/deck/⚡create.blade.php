<?php

use Livewire\Component;
use App\Models\Card;

new class extends Component
{
    public $cards = [];
    public string $name = '';
    public string $description = '';

    public function mount() 
    {
        $this->cards[] = ['expression' => '', 'definition' => ''];
        $this->cards[] = ['expression' => '', 'definition' => ''];
    }

    public function addCard() {
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
        foreach($this->cards as $cardData) {
            $card = new Card();
            $card->expression = $cardData['expression'];
            $card->definition = $cardData['definition'];
            $card->deck_id = $deck->id;
            $card->save();
        }
        Auth::user()->decks()->syncWithoutDetaching([$deck->id]);
        return redirect()->to('/');
    }
};
?>

<flux:card class="space-y-6 max-w-l mx-auto mt-20">
    <div>
        <flux:heading size="lg">Create a new Deck</flux:heading>
        <flux:text class="mt-2">Organize your learning by creating a new deck.</flux:text>
    </div>
    <div class="space-y-6">
        <flux:input label="Deck Name*" placeholder="Enter deck name" wire:model="name" />
        <flux:input label="Description" placeholder="Enter deck description" wire:model="description" />
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($cards as $index => $card)
        <flux:card>
            <flux:input label="expression" wire:model.live="cards.{{ $index }}.expression" class="mb-2"/>
            <flux:input label="definition" wire:model.live="cards.{{ $index }}.definition"/>
        </flux:card>
        @endforeach
    </div>
   
    <flux:card class="flex items-center justify-center border-dashed border-2 hover:border-zinc-400 dark:hover:border-zinc-500 transition-colors p-0 overflow-hidden">
        <flux:button 
            variant="ghost" 
            wire:click="addCard"
            class="w-full h-24 flex flex-col gap-2 items-center justify-center group"
        >
            <span class="text-7xl w-full font-light text-zinc-400 group-hover:text-zinc-800 dark:group-hover:text-white transition-colors">
               +
            </span>
        </flux:button>
    </flux:card>
    <flux:button variant="filled" href="/">Cancel</flux:button>
    <flux:button variant="primary" wire:click="saveDeck">Save Deck</flux:button>
</flux:card>