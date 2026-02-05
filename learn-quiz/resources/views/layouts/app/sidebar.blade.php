<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800 antialiased lg:flex">
        <flux:sidebar sticky collapsible class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.header>
                <flux:sidebar.collapse class="mr-2 !opacity-100" />
            </flux:sidebar.header>
            <flux:sidebar.nav>
                <flux:sidebar.item icon="home" href="/" current>Home</flux:sidebar.item>
                <flux:sidebar.item icon="inbox" badge="12" href="#">Inbox</flux:sidebar.item>
                <flux:sidebar.item icon="document-text" href="#">Documents</flux:sidebar.item>
                <flux:sidebar.item icon="calendar" href="#">Calendar</flux:sidebar.item>
                <flux:sidebar.group expandable icon="star" heading="Favorites" class="grid">
                    <flux:sidebar.item href="#">Marketing site</flux:sidebar.item>
                    <flux:sidebar.item href="#">Android app</flux:sidebar.item>
                    <flux:sidebar.item href="#">Brand guidelines</flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>
            <flux:sidebar.spacer />
            <flux:sidebar.nav>
                <flux:sidebar.item icon="cog-6-tooth" href="#">Settings</flux:sidebar.item>
                <flux:sidebar.item icon="information-circle" href="#">Help</flux:sidebar.item>
            </flux:sidebar.nav>
            <flux:dropdown position="top" align="start" class="max-lg:hidden">
                <flux:sidebar.profile avatar="https://fluxui.dev/img/demo/user.png" name="{{ Auth::user()->name ?? 'Guest'}}" />
                <flux:menu>
                    <flux:menu.item icon="arrow-right-start-on-rectangle" href="/logout">Logout</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            <flux:dropdown position="top" align="start">
                <flux:profile avatar="/img/demo/user.png" />
                <flux:menu>
                    <flux:menu.item icon="arrow-right-start-on-rectangle" href="/logout">Logout</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </flux:header>
        <main class="flex-1 flex justify-center p-6">
            {{ $slot }}
        </main>
        @fluxScripts
    </body>
</html>
