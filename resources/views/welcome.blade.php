<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Beyond the page</title>

    <link rel="icon" href="/favicon.ico" sizes="any" />
    <link rel="icon" href="/favicon.svg" type="image/svg+xml" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles -  adicionar o Tailwind/DaisyUI depois -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body data-theme="light" class="bg-[#FDFDFC] flex flex-col min-h-screen ">
<nav class="navbar bg-[] px-4">
    <div class="flex-1">
        <a href="/" class="text-xl font-semibold normal-case">
            Beyond the Page
        </a>
    </div>
    <div class="flex-none space-x-2">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}">
                    <button class="btn btn-primary btn-outline">Dashboard</button>
                </a>
            @else
                <a href="{{ route('login') }}">
                    <button class="btn btn-primary btn-outline">Log in</button>
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">
                        <button class="btn btn-primary">Register</button>
                    </a>
                @endif
            @endauth
        @endif
    </div>
</nav>
</body>

</html>