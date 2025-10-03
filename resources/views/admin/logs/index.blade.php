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
    <x-layouts.app.custom-sidebar />

    <main class="flex-1 p-6 bg-white text-black">
        <h1 class="text-3xl font-bold mb-4">System Logs</h1>
        <p class="mb-6 text-gray-700">A complete record of actions made by users.</p>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 rounded-lg shadow-sm">
                <thead class="bg-[#444A68] text-white">
                    <tr>
                        <th class="border px-4 py-2 text-left">Date</th>
                        <th class="border px-4 py-2 text-left">User</th>
                        <th class="border px-4 py-2 text-left">Module</th>
                        <th class="border px-4 py-2 text-left">Object ID</th>
                        <th class="border px-4 py-2 text-left">Action</th>
                        <th class="border px-4 py-2 text-left">IP</th>
                        <th class="border px-4 py-2 text-left">Browser</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr class="hover:bg-gray-100">
                            <td class="border px-4 py-2">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="border px-4 py-2">{{ $log->user->name ?? 'Guest' }}</td>
                            <td class="border px-4 py-2">{{ $log->module }}</td>
                            <td class="border px-4 py-2">{{ $log->object_id ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $log->action }}</td>
                            <td class="border px-4 py-2">{{ $log->ip }}</td>
                            <td class="border px-4 py-2 text-xs truncate" title="{{ $log->browser }}">{{ Str::limit($log->browser, 50) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    </main>
</div>