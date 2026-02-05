<?php

use Livewire\Component;
use App\Http\Controllers\LoginController;

new class extends Component
{
    public string $initials = '';
    public string $password = '';

    public function login() {
        $credentials = $this->validate([
            'initials' => ['required'],
            'password' => ['required'],
        ]);
        $fieldType = filter_var($this->initials, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt([$fieldType => $this->initials, 'password' => $this->password])) {
            session()->regenerate();
            return redirect()->intended('/');
        }
        throw ValidationException::withMessages([
            'login' => 'The provided credentials do not match our records.',
        ]);

    }
};
?>

<flux:card class="space-y-6 max-w-xl mx-auto mt-20">
    <div>
        <flux:heading size="lg">Log in to your account</flux:heading>
        <flux:text class="mt-2">Welcome back!</flux:text>
    </div>
    <div class="space-y-6">
        <flux:input label="Username/Email*" wire:model="initials" placeholder="Your username or Email" />
        <flux:field>
            <div class="mb-3 flex justify-between">
                <flux:label>Password*</flux:label>
                <flux:link href="#" variant="subtle" class="text-sm">Forgot password?</flux:link>
            </div>
            <flux:input type="password" wire:model="password" placeholder="Your password" />
            <flux:error name="password" />
        </flux:field>
    </div>
    <div class="space-y-2 flex flex-col">
        <flux:button variant="primary" class="w-full" wire:click="login">Log in</flux:button>
        <flux:link href="/signup" variant="subtle" class="mx-auto">Create new account</flux:link>
    </div>
</flux:card>