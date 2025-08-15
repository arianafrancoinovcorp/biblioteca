<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Beyond the Page</title>

    <link rel="icon" href="/favicon.ico" sizes="any" />
    <link rel="icon" href="/favicon.svg" type="image/svg+xml" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-900 text-gray-300">
    <x-layouts.app.custom-header />

    <div class="flex flex-col lg:flex-row min-h-screen">
        
        <div class="fixed inset-0 bg-black opacity-50 z-20 lg:hidden hidden" id="overlay"></div>

        <x-layouts.app.users-sidebar />

        <main class="flex-1 p-6 lg:pt-10 lg:pb-10 bg-white min-h-screen">
            <button
                class="lg:hidden mb-4 text-gray-800 bg-gray-200 px-3 py-2 rounded-md"
                id="btn-open-sidebar"
                aria-label="Open sidebar">
                Menu
            </button>

            <h1 class="text-4xl font-bold mb-6 text-[#171928]">
                Welcome, {{ Auth::user()->name }}!
            </h1>
            <p class="mb-10 text-[#2e314d]">Here is your dashboard</p>
        </main>
    </div>

    <x-layouts.app.custom-footer />
</body>
</html>
