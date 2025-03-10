@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-block w-full rounded-md border px-4 py-1.5 text-sm dark:border-zinc-600 dark:bg-zinc-700'
            : 'inline-block w-full px-4 py-1.5 text-sm dark:hover:bg-zinc-900 rounded-md';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
