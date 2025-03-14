@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-block w-full rounded-md text-theme shadow-sm border border-theme  px-4 py-1.5 text-sm dark:border-darkTheme dark:text-darkTheme dark:bg-zinc-700 lg:bg-white bg-zinc-100'
            : 'inline-block w-full px-4 py-1.5 text-sm dark:hover:bg-zinc-800 rounded-md ';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
