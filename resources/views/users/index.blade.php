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

    <main class="flex-1 p-4 sm:p-6 lg:pt-10 lg:pb-10 bg-white min-h-screen">

        <button
            class="lg:hidden mb-4 text-gray-800 bg-gray-200 px-3 py-2 rounded-md"
            id="btn-open-sidebar"
            aria-label="Open sidebar">
            Menu
        </button>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-[#171928]">List of users</h1>
                <p class="text-[#2e314d] mt-1">Here you can create, edit and delete any user</p>
            </div>

            <a href="{{ route('users.create') }}"
                class="self-start sm:self-auto bg-[#FE7F63] text-white font-semibold px-5 py-2 rounded hover:bg-[#e76b53] transition whitespace-nowrap">
                Create a new user
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-black rounded-lg shadow-sm overflow-hidden">
                <thead class="bg-gray-600 text-white">
                    <tr>
                        <th class="border bg-[#444A68] px-4 py-2 text-left">ID</th>
                        <th class="border bg-[#444A68] px-4 py-2 text-left">Name</th>
                        <th class="border bg-[#444A68] px-4 py-2 text-left">Email</th>
                        <th class="border bg-[#444A68] px-4 py-2 text-left">Role</th>
                        <th class="border bg-[#444A68] px-4 py-2 text-left rounded-r-lg">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr class="hover:bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2">{{ $user->id }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $user->name }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $user->role }}</td>
                        <td class="border border-gray-300 px-4 py-2 rounded-r-lg space-x-2 whitespace-nowrap">
                            <a href="{{ route('users.edit', $user->id) }}"
                                class="inline-block bg-gray-700 hover:bg-gray-600 text-white font-semibold px-3 py-1 rounded">
                                Edit
                            </a>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-[#FE7F63] hover:bg-[#e76b53] text-white font-semibold px-3 py-1 rounded">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </main>


</div>

<x-layouts.app.custom-footer />