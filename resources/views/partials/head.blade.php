<meta charset="utf-8">

<meta name="application-name" content="{{ config('app.name') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="icon" type="image/png" href="{{ asset('img/logo-birdie-hexagon.png') }}">
<link rel="icon" type="image/x-icon" href="{{ asset('img/logo-birdie-hexagon.png') }}">

@hasSection('title')
    <title>
        @yield('title') - {{ config('app.name') }}
    </title>
@else
    <title>{{ config('app.name') }}</title>
@endif

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<!-- Styles / Scripts -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

@livewireStyles
@filamentStyles

<script>
    let theme = localStorage.getItem('darkMode') || 'system';

    if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>
