<x-filament::button wire:loading.attr='disabled' wire:loading.class='opacity-50 cursor-wait'
    {{ $attributes->merge(['class' => 'btn-primary']) }}>
    {{ $slot }}
</x-filament::button>
