<?php

use App\Models\User;
use Livewire\Component;

new class extends Component
{

    public string $email;
    public string $password;
    public string $username;

    public function mount() {
 
    }

    public function Signup() {
        if(empty($this->username)) {
            $this->addError('username', 'Username is required.');
        }
        if (empty($this->password)) {
            $this->addError('password', 'Password is required.');
        }
        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        $user = new User();
        $user->email = $this->email ?? '';
        $user->name = $this->username;
        $user->password = bcrypt($this->password);
        $user->save();
    }
};
?>

<flux:card class="space-y-6 max-w-xl mx-auto mt-20">
    <div>
        <flux:heading size="lg">Create a new account</flux:heading>
        <flux:text class="mt-2">Welcome!</flux:text>
    </div>
    <div class="space-y-6">
        <flux:input label="Username*" wire:model="username" placeholder="Your username" />
        <flux:input label="Email" type="email" wire:model="email" placeholder="Your email address" />
        <flux:field>
            <div class="mb-3 flex justify-between">
                <flux:label>Password*</flux:label>
            </div>
            <flux:input type="password" wire:model="password" placeholder="Your password" />
            <flux:error name="password" />
        </flux:field>
    </div>
    <div class="space-y-2 flex flex-col">
        <flux:button variant="primary" class="w-full" wire:click="Signup">Signup</flux:button>
        <flux:link href="/Login" variant="subtle" class="mx-auto">Login</flux:link>
    </div>
</flux:card>