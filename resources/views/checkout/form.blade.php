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

<body class="bg-gray-50 min-h-screen">

<div class="container max-w-6xl mx-auto py-8">
    <h1 class="text-3xl font-bold text-[#171928] mb-6">Shopping Cart | Billing information</h1>

    <ul class="steps w-full justify-center steps-horizontal mb-6">
        <li class="step step-primary text-gray-500">Cart</li>
        <li class="step step-primary text-gray-500">Address</li>
        <li class="step text-gray-500">Payment</li>
    </ul>

    @if($cartItems->isEmpty())
        <div class="flex flex-col items-center bg-gray-100 p-6 rounded-xl">
            <p class="text-gray-500 text-lg mb-4">Your cart is empty.</p>
            <a href="{{ route('cart.index') }}" class="bg-[#171928] hover:bg-[#2c2f38] text-white px-6 py-3 rounded-xl font-semibold transition">
                Back to cart
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <div class="bg-white shadow-lg rounded-2xl p-6">
                <h2 class="text-2xl font-semibold text-black mb-4">Order Summary</h2>

                <div class="space-y-4">
                    @foreach($cartItems as $item)
                    <div class="flex justify-between items-center bg-gray-50 rounded-xl p-4 hover:shadow-md transition-shadow">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">{{ $item->book->name }}</h3>
                            <p class="text-gray-500 text-sm">Unit price: €{{ number_format($item->book->price, 2) }}</p>
                        </div>
                        <div class="text-gray-800 font-semibold">
                            {{ $item->quantity }} × €{{ number_format($item->book->price, 2) }} = 
                            €{{ number_format($item->book->price * $item->quantity, 2) }}
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-end mt-6 p-4 bg-gray-100 rounded-xl font-bold text-gray-800 text-lg">
                    Total: €{{ number_format($cartItems->sum(fn($item) => $item->book->price * $item->quantity), 2) }}
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-2xl p-6">
                <h2 class="text-2xl font-semibold text-black mb-4">Shipping Address</h2>

                <form action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="address" class="block font-semibold text-gray-800">Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}"
                               class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-400" required>
                        @error('address')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="city" class="block font-semibold text-gray-800">City</label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}"
                               class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-400" required>
                        @error('city')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="postal_code" class="block font-semibold text-gray-800">Postal Code</label>
                        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}"
                               class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-400" required>
                        @error('postal_code')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="country" class="block font-semibold text-gray-800">Country</label>
                        <input type="text" name="country" id="country" value="{{ old('country') }}"
                               class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-400" required>
                        @error('country')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="bg-[#171928] hover:bg-[#2c2f38] text-white px-6 py-3 rounded-xl font-semibold transition">
                        Continue to Payment
                    </button>
                </form>
            </div>

        </div>
    @endif
</div>
</body>
