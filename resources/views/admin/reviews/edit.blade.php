<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Review - Beyond the page</title>

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

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-[#171928]">Edit Review</h1>
            <p class="text-[#2e314d] mt-1">Approve or reject this review</p>
        </div>

        <div class="max-w-2xl w-full mx-auto bg-gray-800 p-6 rounded-lg shadow-md text-white">
            <h2 class="text-xl font-semibold mb-4">Review details</h2>
            <p><strong>User:</strong> {{ $review->user->name }}</p>
            <p><strong>Book:</strong> {{ $review->bookRequest->book->name ?? '-' }}</p>
            <p><strong>Review Content:</strong> {{ $review->content }}</p>
            <p><strong>Current status:</strong> {{ ucfirst($review->status) }}</p>

            <form method="POST" action="{{ route('admin.reviews.update', $review->id) }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="status" class="text-xl font-semibold mb-4">Status</label>
                    <select name="status" id="status"
                        class="w-full border-gray-300 rounded-md shadow-sm mt-1 bg-gray-800 text-white">
                        <option value="active" @selected($review->status === 'active')>Approve</option>
                        <option value="rejected" @selected($review->status === 'rejected')>Reject</option>
                        <option value="suspended" @selected($review->status === 'suspended')>Pending</option>
                    </select>
                </div>

                <div>
                    <label for="rejection_reason" class="text-xl font-semibold mb-4">
                        Reason for rejection (optional)
                    </label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="3"
                        class="w-full border-gray-300 rounded-md shadow-sm mt-1 bg-gray-800 text-white">{{ old('rejection_reason', $review->rejection_reason) }}</textarea>
                </div>


                <div class="flex justify-end space-x-2">
                    <a href="{{ route('admin.reviews.index') }}"
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold px-3 py-1 rounded">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-[#FE7F63] text-white rounded-md">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>

</html>