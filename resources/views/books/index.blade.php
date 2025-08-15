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

<body class="bg-white min-h-screen flex flex-col items-center">

    <div class="w-full max-w-7xl p-6">
        <form method="GET" class="w-full max-w-4xl mx-auto flex flex-col sm:flex-row items-center gap-4 p-4 justify-center">
            <input type="text"
                name="search"
                placeholder="Search by name or ISBN..."
                value="{{ request('search') }}"
                class="input input-bordered text-white placeholder-white border-blue-900 w-full sm:w-[200px]"
                style="background-color: #444A68;" />

            <select name="author_id"
                class="select select-bordered text-white border-blue-900 w-full sm:w-[150px]"
                style="background-color: #444A68;">
                <option value="">All Authors</option>
                @foreach ($authors as $author)
                <option value="{{ $author->id }}" @selected(request('author_id')==$author->id)>
                    {{ $author->name }}
                </option>
                @endforeach
            </select>

            <select name="sort_by"
                class="select select-bordered text-white border-blue-900 w-full sm:w-[150px]"
                style="background-color: #444A68;">
                <option value="">Sort by</option>
                <option value="name" @selected(request('sort_by')==='name' )>Name</option>
                <option value="isbn" @selected(request('sort_by')==='isbn' )>ISBN</option>
                <option value="price" @selected(request('sort_by')==='price' )>Price</option>
            </select>

            <select name="sort_direction"
                class="select select-bordered text-white border-blue-900 w-full sm:w-[150px]"
                style="background-color: #444A68;">
                <option value="asc" @selected(request('sort_direction')==='asc' )>ASC</option>
                <option value="desc" @selected(request('sort_direction')==='desc' )>DESC</option>
            </select>

            <button type="submit"
                class="btn w-full sm:w-[100px]"
                style="background-color: #FE7F63; border-color: #FE7F63; color: white;">
                Filter
            </button>

            <a href="{{ route('books.export', request()->only(['search', 'publisher_id', 'author_id'])) }}"
                class="btn w-full sm:w-[120px]"
                style="background-color: green; border-color: green; color: white;">
                Export Excel
            </a>
        </form>

        <table class="min-w-full border border-gray-300 text-black rounded-lg shadow-sm overflow-hidden">
            <thead class="bg:  #444A68">
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">ISBN</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Publisher</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Author</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Bibliography</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Cover</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Price</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Book Request</th>
                    @auth
                    @if(auth()->user()->is_admin)
                    <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                    @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">{{ $book->isbn }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="{{ route('books.show', $book->id) }}">
                            {{ $book->name }}
                        </a>
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ $book->publisher->name ?? '-' }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        @foreach ($book->authors as $author)
                        {{ $author->name }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ $book->bibliography }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        @if ($book->cover_image)
                        <img src="{{ $book->cover_image }}" alt="{{ $book->name }}" class="h-12 w-12 rounded" />
                        @else
                        -
                        @endif
                    </td>
                    <td class="border border-gray-300 px-4 py-2">€{{ number_format($book->price, 2) }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        @if($book->isAvailable())
                        <span class="text-green-600 font-semibold">Available</span>
                        @else
                        <span class="text-red-600 font-semibold">Unavailable</span>
                        @endif
                    </td>
                    @auth
                    @if(auth()->user()->is_admin)
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="{{ route('books.edit', $book) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline ml-2"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                    @endif
                    @endauth
                </tr>
                @endforeach
            </tbody>

        </table>
        <div class="mt-4">
            {{ $books->links() }}
        </div>
    </div>

    <x-layouts.app.custom-footer />
</body>