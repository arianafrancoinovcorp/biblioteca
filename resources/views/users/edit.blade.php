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

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-[#171928]">Edit user</h1>
                <p class="text-[#2e314d] mt-1">Edit any data from the user</p>
            </div>

        </div>

        @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="max-w-md mx-auto bg-gray-800 p-6 rounded-lg shadow-md text-white">
            <h3 class="font-semibold text-2xl mb-4">Edit User</h3>

            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block mb-1 font-medium">Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full rounded border border-gray-600 bg-gray-900 px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-white">
                </div>

                <div class="mb-4">
                    <label for="email" class="block mb-1 font-medium">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full rounded border border-gray-600 bg-gray-900 px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-white">
                </div>

                <div class="mb-4">
                    <label for="password" class="block mb-1 font-medium">Password(leave blank to maintain the same password)</label>
                    <input
                        type="password"
                        name="password"
                        class="w-full rounded border border-gray-600 bg-gray-900 px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-white">
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block mb-1 font-medium">Password confirmation</label>
                    <input type="password" name="password_confirmation"
                        class="w-full rounded border border-gray-600 bg-gray-900 px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-white">
                </div>

                <button
                    type="submit"
                    class="w-full bg-[#FE7F63] hover:bg-[#e76b53] font-semibold py-2 rounded transition">
                    Edit User
                </button>
            </form>
        </div>
    </main>
</div>

<x-layouts.app.custom-footer />