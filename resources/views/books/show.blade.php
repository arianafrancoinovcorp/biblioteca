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

<x-layouts.app.custom-header />

<main class="bg-[#F4F5FE] min-h-screen py-10">
    <div class="max-w-6xl mx-auto space-y-10">

        <!-- Book Details -->
        <div class="bg-white shadow-lg rounded-xl p-8 flex flex-col md:flex-row gap-8">
            @if($book->cover_image)
            @php
            $imageUrl = Str::startsWith($book->cover_image, ['http://', 'https://'])
            ? $book->cover_image
            : Storage::url($book->cover_image);
            @endphp
            <img src="{{ $imageUrl }}" alt="{{ $book->name }}" class="h-64 w-auto rounded-xl shadow-md border border-gray-200">
            @endif

            <div class="flex-1 space-y-3">
                <h1 class="text-3xl font-extrabold text-gray-900">{{ $book->name }}</h1>
                <p class="text-gray-700"><strong>ISBN:</strong> {{ $book->isbn }}</p>
                <p class="text-gray-700"><strong>Publisher:</strong> {{ $book->publisher->name ?? '-' }}</p>
                <p class="text-gray-700"><strong>Authors:</strong>
                    @foreach($book->authors as $author)
                    {{ $author->name }}@if(!$loop->last), @endif
                    @endforeach
                </p>
                <p class="text-gray-700"><strong>Bibliography:</strong> {{ $book->bibliography }}</p>
                <p class="text-gray-700"><strong>Price:</strong> €{{ number_format($book->price, 2) }}</p>

                @auth
                @if($book->isAvailable())
                <a href="{{ route('requests.create', $book->id) }}"
                    class="mt-4 inline-block bg-[#FE7F63] hover:bg-[#e76b53] py-2 px-4 rounded text-white font-semibold transition">
                    Request this book
                </a>
                @endif
                <form action="{{ route('cart.add', $book->id) }}" method="POST" class="mt-4">
        @csrf
        <button type="submit"
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-semibold">
            Add to cart
        </button>
    </form>
                @endauth
            </div>
        </div>

        <!-- Reviews -->
        <div class="bg-white rounded-xl shadow-md p-6 mt-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Reviews</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($book->reviews as $review)
                <div class="bg-[#F4F5FE] rounded-lg p-4 hover:shadow-lg transition duration-300">
                    <div class="flex items-center mb-2">
                        <div class="w-10 h-10 rounded-full bg-[#FE7F63] flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>
                        <p class="ml-3 font-semibold text-gray-800">{{ $review->user->name }}</p>
                    </div>
                    <p class="text-gray-700">{{ $review->content }}</p>
                </div>
                @empty
                <p class="text-gray-500 col-span-full">No active reviews yet.</p>
                @endforelse
            </div>
        </div>


        <!-- Related Books -->
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Related Books</h2>
            <div class="flex flex-wrap justify-center gap-6">
                @foreach($relatedBooks as $related)
                @if($related->cover_image)
                @php
                $imageUrl = Str::startsWith($related->cover_image, ['http://', 'https://'])
                ? $related->cover_image
                : Storage::url($related->cover_image);
                @endphp
                <a href="{{ route('books.show', $related->id) }}"
                    class="group w-40 bg-white rounded-xl shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <figure class="h-56 overflow-hidden">
                        <img src="{{ $imageUrl }}" alt="{{ $related->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                    </figure>
                    <div class="p-3 text-center">
                        <h3 class="text-sm font-semibold text-gray-800 break-words">{{ $related->name }}</h3>
                    </div>
                </a>
                @endif
                @endforeach
            </div>
        </div>

    </div>
</main>

<x-layouts.app.custom-footer />