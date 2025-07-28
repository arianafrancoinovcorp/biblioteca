<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Mundo das Histórias</title>

    <link rel="icon" href="/favicon.ico" sizes="any" />
    <link rel="icon" href="/favicon.svg" type="image/svg+xml" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles -  adicionar o Tailwind/DaisyUI depois -->
</head>
<body class="bg-[#FDFDFC] text-[#1b1b18] flex flex-col items-center justify-center min-h-screen p-6 lg:p-8">
    @if (Route::has('login'))
    <nav class="w-full max-w-4xl flex justify-end gap-4 max-w-[335px] mb-6">
        @auth
            <a href="{{ url('/dashboard') }}" class="border border-[#19140035] rounded-sm px-5 py-1.5 text-sm hover:border-[#1915014a]">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="text-[#1b1b18] border border-transparent rounded-sm px-5 py-1.5 text-sm hover:border-[#19140035]">Log in</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="border border-[#19140035] rounded-sm px-5 py-1.5 text-sm hover:border-[#1915014a]">Register</a>
            @endif
        @endauth
    </nav>
    @endif
</body>
</html>
