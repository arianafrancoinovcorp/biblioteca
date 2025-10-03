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

<div class="flex flex-col lg:flex-row min-h-screen bg-gray-100 text-gray-900">

    <div class="fixed inset-0 bg-black opacity-50 z-20 lg:hidden hidden" id="overlay"></div>

    @if(auth()->user()->role === 'admin')
    <x-layouts.app.custom-sidebar />
    @else
    <x-layouts.app.users-sidebar />
    @endif

    <main class="flex-1 p-4 sm:p-6 lg:pt-10 lg:pb-10 bg-white min-h-screen">
        <button class="lg:hidden mb-4 text-gray-800 bg-gray-200 px-3 py-2 rounded-md"
            id="btn-open-sidebar"
            aria-label="Open sidebar">
            Menu
        </button>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-800">List of Book Requests</h1>
                <p class="text-gray-600 mt-1">Here you can see all book requests</p>
            </div>
        </div>
        @if(auth()->user()->role === 'admin')
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-100 text-blue-800 p-4 rounded shadow flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <span class="text-sm font-medium"># Active Book Requests</span>
                    <div class="text-2xl font-bold">{{ $activeRequestsCount }}</div>
                </div>
            </div>

            <div class="bg-green-100 text-green-800 p-4 rounded shadow flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>
                <div>
                    <span class="text-sm font-medium"># Last 30 days Requests</span>
                    <div class="text-2xl font-bold">{{ $last30DaysCount }}</div>
                </div>
            </div>

            <div class="bg-yellow-100 text-yellow-800 p-4 rounded shadow flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <span class="text-sm font-medium"># Books Returned Today</span>
                    <div class="text-2xl font-bold">{{ $returnedTodayCount }}</div>
                </div>
            </div>
        </div>
        @endif


        <div class="flex-1 overflow-auto rounded-lg shadow">
            <table class="min-w-full bg-white divide-y divide-gray-200 text-gray-900">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($requests as $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $r->request_number ?? $r->id }}</td>
                        <td class="px-6 py-4 max-w-xs truncate">{{ $r->book->name ?? '—' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $r->user->name ?? '—' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $r->start_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $r->due_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                {{ $r->status == 'active' ? 'bg-blue-100 text-blue-800' : ($r->status == 'returned' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex items-center gap-2">
                            <a href="{{ route('requests.show', $r->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View</a>

                            @if($r->status !== 'returned' && $r->user_id === auth()->id())
                            <form action="{{ route('requests.return', $r->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded text-sm">
                                    Return Book
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No Book Requests at the moment</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
        <div class="mt-4">
            {{ $requests->links() }}
        </div>
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
        @endif

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    </main>
</div>

<x-layouts.app.custom-footer />