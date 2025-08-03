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
<style>
    input::placeholder {
        color: white;
    }
</style>


<x-layouts.app.custom-header />

<body class="bg-white min-h-screen flex flex-col items-center">
    <div class="w-full max-w-7xl p-6">
        <form method="GET" class="w-full max-w-4xl mx-auto flex flex-col sm:flex-row items-center gap-4 p-4 justify-center">
            <input
                type="text"
                name="search"
                placeholder="Search by name..."
                value="{{ request('search') }}"
                class="input input-bordered text-white placeholder-white border-blue-900 w-full sm:w-[200px]"
                style="background-color: #444A68;" />

            <select
                name="sort_by"
                class="select select-bordered text-white border-blue-900 w-full sm:w-[150px]"
                style="background-color: #444A68;">
                <option value="">Sort by</option>
                <option value="name" @selected(request('sort_by')==='name' )>Name</option>
            </select>

            <select
                name="sort_direction"
                class="select select-bordered text-white border-blue-900 w-full sm:w-[150px]"
                style="background-color: #444A68;">
                <option value="asc" @selected(request('sort_direction')==='asc' )>ASC</option>
                <option value="desc" @selected(request('sort_direction')==='desc' )>DESC</option>
            </select>

            <button
                type="submit"
                class="btn w-full sm:w-[100px]"
                style="background-color: #FE7F63; border-color: #FE7F63; color: white;">
                Filter
            </button>
        </form>


        <table class="min-w-full border border-gray-300 text-black rounded-lg shadow-sm overflow-hidden">
            <thead class="bg:  #444A68">
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">Photo</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($authors as $author)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2 rounded-l-lg">
                        <div class="avatar">
                            <div class="mask mask-squircle h-12 w-12 overflow-hidden rounded-lg">
                                <img src="{{ $author->photo }}" alt="{{ $author->name }}" class="object-cover h-full w-full" />
                            </div>
                        </div>
                    </td>
                    <td class="border border-gray-300 px-4 py-2 rounded-r-lg">{{ $author->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $authors->links() }}
        </div>
    </div>

</body>


<x-layouts.app.custom-footer />