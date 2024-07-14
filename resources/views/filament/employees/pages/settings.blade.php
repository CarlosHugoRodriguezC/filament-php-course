
<x-filament-panels::page>
    <h1 class="text-xl font-bold text-gray-400">Custom Settings</h1>

    {{ $count }}

    <button wire:click="incrementBy(+1)">add 1</button>
    <button wire:click="incrementBy(-1)">remove 1</button>

</x-filament-panels::page>
