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
                <h2 class="text-2xl font-bold mb-6 border-b pb-2">Request a Book: {{ $book->name }}</h2>

                <div class="flex flex-col md:flex-row gap-6">

                    <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">

                        <div class="mb-4">
                            <label>Upload Photo (optional):</label><br>
                            <input type="file" name="photo">
                        </div>

                        <button class="btn btn-custom" type="submit">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<x-layouts.app.custom-footer />