<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <!-- Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-900">
<x-banner/>

<div class="min-h-screen bg-gray-900 pt-16">
    @livewire('navigation-menu')

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif
    @if(session()->has('message'))
        <script>
            var notyf = new Notyf({
                dismissible: true,
                duration: 4000,
            });
            notyf.success("{{session()->get('message')}}");
        </script>
    @endif
    @if(session()->has('error'))
        <script>
            var notyf = new Notyf({
                dismissible: true,
                duration: 4000,
            });
            notyf.error("{{session()->get('error')}}");;
        </script>
    @endif

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
</div>
<script>
    window.onhashchange = function() {
        if (window.innerDocClick) {
            console.log(123);
        } else {
            console.log(321);
        }
    }
</script>
@stack('modals')

@livewireScripts
</body>
</html>
