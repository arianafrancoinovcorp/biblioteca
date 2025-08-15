<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Beyond the page</title>

    <link rel="icon" href="/favicon.ico" sizes="any" />
    <link rel="icon" href="/favicon.svg" type="image/svg+xml" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />


    @vite(['resources/css/app.css'])

</head>

<x-layouts.app.custom-header />

<div class="flex flex-col lg:flex-row min-h-screen bg-gray-900 text-gray-300">

    <div class="fixed inset-0 bg-black opacity-50 z-20 lg:hidden hidden" id="overlay"></div>

    <x-layouts.app.custom-sidebar />

    <main class="flex-1 p-6 lg:pt-10 lg:pb-10 bg-white min-h-screen">
        <button
            class="lg:hidden mb-4 text-gray-800 bg-gray-200 px-3 py-2 rounded-md"
            id="btn-open-sidebar"
            aria-label="Open sidebar">
            Menu
        </button>

        <h1 class="text-4xl font-bold mb-6 text-[#171928]">Welcome, {{ Auth::user()->name }}!</h1>
        <p class="mb-10 text-[#2e314d]">Here is your management page</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="font-semibold text-xl mb-3 text-white">Books</h3>
                <p class="text-gray-300">Create, edit and delete books.</p>
                <a href="{{ route('admin.books.index') }}">
                    <button class="btn btn-custom mt-5">Go to books</button>
                </a>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="font-semibold text-xl mb-3 text-white">Users</h3>
                <p class="text-gray-300">Create, edit and delete users.</p>
                <a href="{{ route('users.index') }}">
                    <button class="btn btn-custom mt-5">Go to users</button>
                </a>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="font-semibold text-xl mb-3 text-white">Book Requests</h3>
                <p class="text-gray-300">View and manage book requests.</p>
                <a href="{{ route('requests.index') }}">
                    <button class="btn btn-custom mt-5">Go to book requests</button>
                </a>
            </div>
        </div>
    </main>
</div>

<script>
    // Script simples para abrir/fechar sidebar em telas pequenas
    const btnOpen = document.getElementById('btn-open-sidebar');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    btnOpen.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });
</script>



<x-layouts.app.custom-footer />