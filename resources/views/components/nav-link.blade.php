@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-block flex justify-between items-center w-full rounded-md text-theme shadow-sm border border-theme  px-4 py-1.5 text-sm dark:border-darkTheme dark:text-darkTheme dark:bg-zinc-700 lg:bg-white bg-zinc-100'
            : 'inline-block w-full px-4 py-1.5 text-sm dark:hover:bg-zinc-800 rounded-md text-theme hover:bg-zinc-100 lg:hover:bg-zinc-200 dark:text-darkTheme flex justify-between items-center';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
