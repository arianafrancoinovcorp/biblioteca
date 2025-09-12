<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Beyond the page - Reviews</title>

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
        <button class="lg:hidden mb-4 text-gray-800 bg-gray-200 px-3 py-2 rounded-md"
            id="btn-open-sidebar"
            aria-label="Open sidebar">
            Menu
        </button>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div class="text-center sm:text-left mb-4 sm:mb-0">
                <h1 class="text-3xl sm:text-4xl font-bold text-[#171928]">List of Reviews</h1>
                <p class="text-[#2e314d] mt-1">Here you can check and manage reviews submitted by users</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-black rounded-lg shadow-sm overflow-hidden">
                <thead class="bg-[#444A68] text-white">
                    <tr>
                        <th class="border px-4 py-2 text-left">User</th>
                        <th class="border px-4 py-2 text-left">Book</th>
                        <th class="border px-4 py-2 text-left">Content</th>
                        <th class="border px-4 py-2 text-left">Status</th>
                        <th class="border px-4 py-2 text-left">Submitted at</th>
                        <th class="border px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $review)
                        <tr class="hover:bg-gray-100">
                            <td class="border px-4 py-2">{{ $review->user->name }}</td>
                            <td class="border px-4 py-2">{{ $review->bookRequest->book->name ?? '-' }}</td>
                            <td class="border px-4 py-2 max-w-xs truncate" title="{{ $review->content }}">
                                {{ Str::limit($review->content, 50) }}
                            </td>
                            <td class="border px-4 py-2">
                                @if ($review->status === 'active')
                                    <span class="text-green-600 font-semibold">Approved</span>
                                @elseif ($review->status === 'rejected')
                                    <span class="text-red-600 font-semibold">Rejected</span>
                                @else
                                    <span class="text-yellow-600 font-semibold">Pending</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2">{{ $review->created_at->format('d/m/Y H:i') }}</td>
                            <td class="border px-4 py-2 space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.reviews.show', $review->id) }}"
                                    class="inline-block bg-gray-700 hover:bg-gray-600 text-white font-semibold px-3 py-1 rounded">
                                    View
                                </a>
                                <a href="{{ route('admin.reviews.edit', $review->id) }}"
                                    class="inline-block bg-[#FE7F63] text-white font-semibold px-3 py-1 rounded">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    </main>
</div>
</body>

</html>
