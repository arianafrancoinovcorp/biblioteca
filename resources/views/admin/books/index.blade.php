<!DOCTYPE html>
<html lang="en">

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

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div class="text-center sm:text-left mb-4 sm:mb-0">
                <h1 class="text-3xl sm:text-4xl font-bold text-[#171928]">List of Books</h1>
                <p class="text-[#2e314d] mt-1">Here you can create, edit and delete any book</p>
            </div>
            <a href="{{ route('books.create') }}"
                class="bg-[#FE7F63] text-white font-semibold px-5 py-2 rounded hover:bg-[#e76b53] transition whitespace-nowrap">
                Create a new book
            </a>
        </div>


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

        <div class="overflow-x-auto w-full rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200 table-auto">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ISBN</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Publisher</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bibliography</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 text-gray-900">
            @foreach ($books as $book)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 whitespace-nowrap">{{ $book->isbn }}</td>
                <td class="px-4 py-2 max-w-[150px] truncate">{{ $book->name }}</td>
                <td class="px-4 py-2 whitespace-nowrap">{{ $book->publisher->name ?? '-' }}</td>
                <td class="px-4 py-2 max-w-[200px] truncate">
                    @foreach ($book->authors as $author)
                        {{ $author->name }}@if (!$loop->last), @endif
                    @endforeach
                </td>
                <td class="px-4 py-2 max-w-[250px] truncate">{{ $book->bibliography }}</td>
                <td class="px-4 py-2 whitespace-nowrap">
                    @if ($book->cover_image)
                        <img src="{{ $book->cover_image }}" alt="{{ $book->name }}" class="h-12 w-12 rounded object-cover" />
                    @else
                        -
                    @endif
                </td>
                <td class="px-4 py-2 whitespace-nowrap">€{{ number_format($book->price, 2) }}</td>
                <td class="px-4 py-2 whitespace-nowrap">
                    @if ($book->isAvailable())
                        <span class="text-green-600 font-semibold">Available</span>
                    @else
                        <span class="text-red-600 font-semibold">Unavailable</span>
                    @endif
                </td>
                <td class="px-4 py-2 whitespace-nowrap flex gap-2">
                    <a href="{{ route('books.edit', $book->id) }}" class="bg-gray-700 hover:bg-gray-600 text-white px-3 py-1 rounded">Edit</a>
                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-[#FE7F63] hover:bg-[#e76b53] text-white px-3 py-1 rounded">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


        {{-- Pagination --}}
        <div class="mt-6">
            {{ $books->links() }}
        </div>
    </main>
</div>
</body>

</html>