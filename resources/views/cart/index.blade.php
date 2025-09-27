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
        <h1 class="text-3xl font-bold text-[#171928] mb-4">Shopping Cart</h1>

        <ul class="steps w-full justify-center steps-horizontal mb-6">
            <li class="step step-primary text-white text-gray-600">Cart</li>
            <li class="step text-gray-500">Address</li>
            <li class="step text-gray-500">Payment</li>
        </ul>
        <div class="bg-white shadow-lg rounded-2xl p-6">



            @if($cartItems->isEmpty())
            <div class="flex justify-between items-center bg-gray-100 p-6 rounded-xl">
                <p class="text-gray-500 text-lg">Your cart is empty!</p>
                <a href="{{ route('books.index') }}"
                    class="bg-[#171928] hover:bg-[#2c2f38] text-white px-6 py-3 rounded-xl font-semibold transition">
                    Start Shopping
                </a>
            </div>
            @else
            <div class="space-y-4">

                @foreach($cartItems as $item)
                <div class="flex flex-col sm:flex-row justify-between items-center bg-white shadow rounded-xl p-4 hover:shadow-lg transition-shadow">

                    <div class="flex-1">
                        <h2 class="text-lg font-semibold text-gray-800">{{ $item->book->name }}</h2>
                        <p class="text-gray-500 mt-1">Unit price: {{ number_format($item->book->price, 2) }}€</p>
                    </div>

                    <div class="flex items-center space-x-2 mt-3 sm:mt-0">

                        <form action="{{ route('cart.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="quantity" value="{{ $item->quantity }}" id="input-decrease-{{ $item->id }}">
                            <button type="submit"
                                onclick="document.getElementById('input-decrease-{{ $item->id }}').value = parseInt(document.getElementById('quantity-{{ $item->id }}').innerText) - 1"
                                class="bg-gray-200 hover:bg-gray-300 w-10 h-10 flex items-center justify-center rounded-lg font-bold text-gray-800">-</button>
                        </form>

                        <span id="quantity-{{ $item->id }}" class="w-10 h-10 flex items-center justify-center text-gray-800 font-semibold">
                            {{ $item->quantity }}
                        </span>

                        <form action="{{ route('cart.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="quantity" value="{{ $item->quantity }}" id="input-increase-{{ $item->id }}">
                            <button type="submit"
                                onclick="document.getElementById('input-increase-{{ $item->id }}').value = parseInt(document.getElementById('quantity-{{ $item->id }}').innerText) + 1"
                                class="bg-gray-200 hover:bg-gray-300 w-10 h-10 flex items-center justify-center rounded-lg font-bold text-gray-800">+</button>
                        </form>
                    </div>

                    <div class="mt-3 sm:mt-0 sm:ml-6 text-gray-700 font-semibold">
                        €<span id="subtotal-{{ $item->id }}" data-price="{{ $item->book->price }}">
                            {{ number_format($item->book->price * $item->quantity, 2) }}
                        </span>
                    </div>

                    <div class="mt-3 sm:mt-0 sm:ml-4">
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" />
                                </svg>
                            </button>
                        </form>
                    </div>

                </div>
                @endforeach

                <div id="cart-total" class="flex justify-end items-center mt-4 p-4 bg-gray-100 rounded-xl font-bold text-gray-800 text-lg">
                    Total: €<span id="total-value">
                        {{ number_format($cartItems->sum(fn($item) => $item->book->price * $item->quantity), 2) }}
                    </span>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('checkout.form') }}" class="bg-[#171928] hover:bg-[#2c2f38] text-white px-6 py-3 rounded-xl font-semibold transition">
                        Continue for payment
                    </a>
                </div>

            </div>
            @endif

        </div>
    </div>

    <script>
        function updateTotal() {
            let total = 0;
            document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
                total += parseFloat(el.innerText);
            });
            document.getElementById('total-value').innerText = total.toFixed(2);
        }
    </script>

</body>