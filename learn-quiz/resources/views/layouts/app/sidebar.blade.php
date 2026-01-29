<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
   <body 
    x-data="{ 
        sidebarOpen: window.innerWidth > 1024,
        isDesktop: window.innerWidth > 1024 
    }" 
    {{-- Update isDesktop state if the user resizes their browser --}}
    @resize.window="isDesktop = window.innerWidth > 1024"
    class="min-h-screen bg-white dark:bg-zinc-800 relative"
>
    
    <div 
        x-show="!sidebarOpen" 
        x-transition
        class="absolute top-4 left-4 z-[60]"
    >
    <flux:sidebar.header>
            <flux:sidebar.collapse @click="sidebarOpen = true" />
        </flux:sidebar.header>
    </div>

    <div 
        x-show="sidebarOpen" 
        x-cloak
        {{-- Only close on click-away IF we are NOT on desktop --}}
        @click.away="if (!isDesktop) sidebarOpen = false"
        
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"

         
        class="fixed inset-y-0 left-0 z-50 w-64 shadow-2xl bg-zinc-900 border-r border-zinc-700"
    >
        <flux:sidebar.header class="mx-4 mt-4 mb-2">
            <flux:sidebar.collapse @click="sidebarOpen = false" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="#" current>Home</flux:sidebar.item>
        </flux:sidebar.nav>
    </div>

    {{-- If sidebar is relative (desktop), this will naturally sit next to it. 
         If sidebar is fixed (mobile), this will fill the screen. --}}
    <main class="flex-1">
        {{ $slot }}
    </main>

    @fluxScripts
</body>
</html>
