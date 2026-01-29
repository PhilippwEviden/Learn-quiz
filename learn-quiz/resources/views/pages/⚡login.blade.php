<?php

use Livewire\Component;

new class extends Component
{
    //
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
                <flux:label>Password</flux:label>
                <flux:link href="#" variant="subtle" class="text-sm">Forgot password?</flux:link>
            </div>
            <flux:input type="password" placeholder="Your password" />
            <flux:error name="password" />
        </flux:field>
    </div>
    <div class="space-y-2 flex flex-col">
        <flux:button variant="primary" class="w-full">Log in</flux:button>
        <flux:link href="/Signup" variant="subtle" class="mx-auto">Create new account</flux:link>
    </div>
</flux:card>