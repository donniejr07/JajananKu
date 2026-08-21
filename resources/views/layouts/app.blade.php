<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'JajananKu')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-body-md text-body-md text-on-surface antialiased flex h-screen overflow-hidden">

    <x-sidebar />

    <main class="flex-1 flex flex-col md:ml-sidebar-width h-screen overflow-y-auto" x-data="{}">
        <x-topbar />

        <div class="mt-16 p-gutter max-w-container-max mx-auto w-full flex flex-col gap-stack-lg pb-margin-page">
            @if (session('status'))
                <x-flash-message :message="session('status')" />
            @endif

            @yield('content')
            {{ $slot ?? '' }}
        </div>
    </main>

    <livewire:transaction-form />

    @livewireScripts
</body>
</html>
