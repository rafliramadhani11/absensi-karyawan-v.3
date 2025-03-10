<div>

    @if ($errors->any())
    <div class="py-2 mb-3 text-center rounded-md dark:bg-red-500">
        <ul class="text-xs text-red-600 dark:text-white">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form wire:submit="login">
        {{ $this->form }}

        <x-primary-button type="submit" class="w-full mt-6 ">
            Sign in
        </x-primary-button>
    </form>

    <x-filament-actions::modals />
</div>