<x-layouts::app.sidebar :title="$title ?? 'test'">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
