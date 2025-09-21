<!DOCTYPE html>
<html>

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

<div class="flex flex-col lg:flex-row min-h-screen bg-gray-100 text-gray-900">

    <div class="fixed inset-0 bg-black opacity-50 z-20 lg:hidden hidden" id="overlay"></div>

    @if(auth()->user()->role === 'admin')
    <x-layouts.app.custom-sidebar />
    @else
    <x-layouts.app.users-sidebar />
    @endif

    <main class="flex-1 p-4 sm:p-6 lg:pt-10 lg:pb-10 bg-white min-h-screen">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-800">
                    Book Request Number #{{ $request->request_number ?? $request->id }}
                </h1>
                <p class="text-gray-600 mt-1">Here you can see all details from the book request</p>
            </div>
        </div>

        <p><strong>Book:</strong> {{ $request->book->name ?? '—' }}</p>
        <p><strong>User:</strong> {{ $request->user->name ?? '—' }} ({{ $request->user->email ?? '' }})</p>
        <p><strong>Start Date:</strong> {{ $request->start_date }}</p>
        <p><strong>Due date:</strong> {{ $request->due_date }}</p>
        <p><strong>Status:</strong> {{ ucfirst($request->status) }}</p>

        @if($request->photo)
        <p><strong>Photo:</strong><br>
            <img src="{{ asset('storage/' . $request->photo) }}" alt="photo" style="max-width:200px">
        </p>
        @endif

        @if(isset($canReview) && $canReview)

        <hr class="my-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-3">Leave a Review</h2>
        <form method="POST" action="{{ route('reviews.store', $request->id) }}">
            @csrf
            <textarea name="content" rows="4"
                class="w-full border rounded-lg p-2 text-gray-800 focus:ring focus:ring-blue-300"
                placeholder="Write your review here..." required></textarea>

            <div class="mt-4 flex justify-end gap-x-2">
                <button
                    class="btn btn-custom" type="submit">Submit Review
                </button>
                <a href="{{ route('requests.index') }}"
                    class="bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                    Back
                </a>
            </div>
        </form>

        @endif

        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
        @endif
    </main>
</div>

</html>