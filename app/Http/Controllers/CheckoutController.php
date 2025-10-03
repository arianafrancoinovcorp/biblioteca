<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Helpers\LogHelper;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showForm()
    {
        $cartItems = CartItem::with('book')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(fn($item) => $item->book->price * $item->quantity);

        return view('checkout.form', compact('cartItems', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        $cartItems = CartItem::with('book')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        session([
            'checkout_address' => $request->address,
            'checkout_city' => $request->city,
            'checkout_postal_code' => $request->postal_code,
            'checkout_country' => $request->country,
            'checkout_cart_items' => $cartItems->map(fn($item) => [
                'book_id' => $item->book->id,
                'quantity' => $item->quantity,
                'price' => $item->book->price,
            ])->toArray(),
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = $cartItems->map(fn($item) => [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => ['name' => $item->book->name],
                'unit_amount' => $item->book->price * 100,
            ],
            'quantity' => $item->quantity,
        ])->toArray();

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cart.index'),
        ]);
        LogHelper::record('Orders', null, 'User started checkout process');

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('cart.index')->with('error', 'Payment session missing.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = StripeSession::retrieve($sessionId);
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Could not verify payment.');
        }

        if ($session->payment_status !== 'paid') {
            return redirect()->route('cart.index')->with('error', 'Payment not completed.');
        }

        $cartItems = session('checkout_cart_items', []);

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'address' => session('checkout_address'),
            'city' => session('checkout_city'),
            'postal_code' => session('checkout_postal_code'),
            'country' => session('checkout_country'),
            'status' => 'paid',
            'total_price' => collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']),
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $item['book_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
            ]);
        }

        CartItem::where('user_id', Auth::id())->delete();

        session()->forget([
            'checkout_address',
            'checkout_city',
            'checkout_postal_code',
            'checkout_country',
            'checkout_cart_items',
        ]);
        LogHelper::record('Orders', $order->id, 'User completed checkout and payment successfully');

        return view('checkout.success', compact('order'));
    }
}
