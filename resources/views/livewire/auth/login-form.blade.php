<div>

    @if ($errors->any())
        <div class="mb-3 rounded-md py-2 text-center dark:bg-red-500">
            <ul class="text-xs text-red-600 dark:text-white">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit="login">
        {{ $this->form }}

        <x-primary-button type="submit" class="mt-6 w-full">
            Sign in
        </x-primary-button>
    </form>


</div>
