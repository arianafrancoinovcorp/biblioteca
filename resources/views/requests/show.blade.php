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

    <x-layouts.app.custom-sidebar />

    <main class="flex-1 p-4 sm:p-6 lg:pt-10 lg:pb-10 bg-white min-h-screen">


        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-800">Book Request Number #{{ $request->request_number ?? $request->id }}</h1>
                <p class="text-gray-600 mt-1">Here you can see all details from the book request</p>
            </div>
        </div>

        <p><strong>Book:</strong> {{ $request->book->name ?? '—' }}</p>
        <p><strong>User:</strong> {{ $request->user->name ?? '—' }} ({{ $request->user->email ?? '' }})</p>
        <p><strong>Start Date:</strong> {{ $request->start_date }}</p>
        <p><strong>Due date:</strong> {{ $request->due_date }}</p>
        <p><strong>Status:</strong> {{ $request->status }}</p>

        @if($request->photo)
        <p><strong>Photo:</strong><br>
            <img src="{{ asset('storage/' . $request->photo) }}" alt="photo" style="max-width:200px">
        </p>
        @endif

        <p><strong>Notes:</strong><br>{{ $request->notes }}</p>

        <p><a href="{{ route('requests.index') }}">Back</a></p>
        </body>

</html>