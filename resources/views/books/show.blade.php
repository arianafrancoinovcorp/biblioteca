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
<main class="flex-1 p-6 lg:pt-10 lg:pb-10 bg-white min-h-screen">
    <div class="max-w-5xl mx-auto mt-8">
        <div class="max-w-5xl mx-auto mt-8">
            <div class="bg-gray-800 shadow-md rounded p-6">
                <h2 class="text-2xl font-bold mb-6 border-b pb-2">Book Details</h2>

                <div class="flex flex-col md:flex-row gap-6">

                    @if($book->cover_image)
                    @php
                    $imageUrl = Str::startsWith($book->cover_image, ['http://', 'https://'])
                    ? $book->cover_image
                    : Storage::url($book->cover_image);
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $book->name }}" class="h-64 w-auto rounded shadow-md">
                    @endif

                    <div class="flex-1 space-y-3">
                        <h1 class="text-3xl font-semibold text-color-black">{{ $book->name }}</h1>
                        <p><strong>ISBN:</strong> {{ $book->isbn }}</p>
                        <p><strong>Publisher:</strong> {{ $book->publisher->name ?? '-' }}</p>
                        <p><strong>Authors:</strong>
                            @foreach($book->authors as $author)
                            {{ $author->name }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                        <p><strong>Bibliography:</strong> {{ $book->bibliography }}</p>
                        <p><strong>Price:</strong> €{{ number_format($book->price, 2) }}</p>

                        @auth
                        @if($book->isAvailable())
                        <a href="{{ route('requests.create', $book->id) }}"
                            class="mt-4 inline-block bg-[#FE7F63] hover:bg-[#e76b53] py-2 px-4 rounded text-white font-semibold">
                            Request this book
                        </a>

                        @endif

                        @endauth

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<x-layouts.app.custom-footer />