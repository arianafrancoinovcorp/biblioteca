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
<body class="bg-gray-50 min-h-screen flex flex-col">

    <x-layouts.app.custom-header />

    <main class="flex-1 flex flex-col items-center py-8">
        <div class="container max-w-4xl mx-auto">
            <div class="bg-white shadow-lg rounded-2xl p-6 text-center">

                <h1 class="text-3xl font-bold text-[#171928] mb-4">Checkout Completed</h1>

                <ul class="steps w-full justify-center steps-horizontal mb-6">
                    <li class="step step-primary">Cart</li>
                    <li class="step step-primary">Address</li>
                    <li class="step step-primary">Payment</li>
                </ul>

                <div class="mb-6">
                    <p class="text-lg text-gray-700">Thank you for your order! Your payment was processed successfully.</p>
                </div>

                <h2 class="text-2xl font-semibold text-gray-900 mb-4 text-left">Order Summary</h2>
                <div class="space-y-3 mb-6 text-left">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
                            <span class="text-gray-800 font-medium">
                                {{ $item->book->name }} 
                                <span class="text-sm text-gray-500">(x{{ $item->quantity }})</span>
                            </span>
                            <span class="text-gray-700 font-semibold">
                                €{{ number_format($item->unit_price * $item->quantity, 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end bg-gray-100 rounded-xl p-4 font-bold text-gray-800 text-lg mb-6">
                    Total Paid: €{{ number_format($order->total_price, 2) }}
                </div>


                <a href="{{ route('books.index') }}" 
                   class="bg-[#171928] hover:bg-[#2c2f38] text-white px-6 py-3 rounded-xl font-semibold transition">
                   Continue Shopping
                </a>
            </div>
        </div>
    </main>
</body>
</html>
