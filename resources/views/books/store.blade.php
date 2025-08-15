
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
                <h1 class="text-3xl sm:text-4xl font-bold text-[#171928]">Create a New Book</h1>
                <p class="text-[#2e314d] mt-1">Here you can add a new book to the system</p>
            </div>
        </div>

        <div class="max-w-2xl mx-auto bg-gray-800 p-8 rounded-lg shadow-md text-white">
            <h3 class="font-semibold text-2xl mb-6">Create a book</h3>

            @if ($errors->any())
            <div class="bg-red-500 text-white p-3 rounded mb-6">
                <strong>There were some problems:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block mb-1 font-medium">Title</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        class="w-full rounded border border-gray-600 bg-gray-900 px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-white"
                        placeholder="Book title"
                        required />
                </div>

                <div>
                    <label for="isbn" class="block mb-1 font-medium">ISBN</label>
                    <input
                        id="isbn"
                        name="isbn"
                        type="text"
                        value="{{ old('isbn') }}"
                        class="w-full rounded border border-gray-600 bg-gray-900 px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-white"
                        placeholder="ISBN code"
                        required />
                </div>

                <div>
                    <label for="publisher_id" class="block mb-1 font-medium">Publisher</label>
                    <select
                        id="publisher_id"
                        name="publisher_id"
                        class="w-full rounded border border-gray-600 bg-gray-900 px-3 py-2 text-white"
                        required>
                        <option value="">-- Select --</option>
                        @foreach ($publishers as $publisher)
                        <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                            {{ $publisher->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="authors" class="block mb-1 font-medium">Authors:</label>
                    <select
                        id="authors"
                        name="author_ids[]"
                        multiple
                        size="5"
                        class="w-full rounded border border-gray-600 bg-gray-900 px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-white">
                        @foreach ($authors as $author)
                        <option value="{{ $author->id }}" {{ collect(old('author_ids'))->contains($author->id) ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="bibliography" class="block mb-1 font-medium">Bibliography</label>
                    <textarea
                        id="bibliography"
                        name="bibliography"
                        class="w-full rounded border border-gray-600 bg-gray-900 px-3 py-2 text-white"
                        rows="3">{{ old('bibliography') }}</textarea>
                </div>

                <div>
                    <label for="cover_image" class="block mb-1 font-medium">Cover Image</label>
                    <input
                        id="cover_image"
                        type="file"
                        name="cover_image"
                        class="w-full text-white" />
                </div>

                <div>
                    <label for="price" class="block mb-1 font-medium">Price (€)</label>
                    <input
                        id="price"
                        type="number"
                        name="price"
                        step="0.01"
                        value="{{ old('price') }}"
                        class="w-full rounded border border-gray-600 bg-gray-900 px-3 py-2 text-white"
                        required />
                </div>

                <button
                    type="submit"
                    class="bg-[#FE7F63] hover:bg-[#e76b53] text-white font-semibold px-4 py-2 rounded">
                    Create Book
                </button>
            </form>
        </div>

    </main>
</div>