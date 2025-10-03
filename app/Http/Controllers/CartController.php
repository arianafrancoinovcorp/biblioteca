<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendCartReminderEmail;
use App\Helpers\LogHelper;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cartItems = CartItem::with('book')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    public function add(Book $book)
    {
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();

            LogHelper::record('Cart', $cartItem->id, "Updated quantity for book '{$book->name}' in cart");
        } else {
            $cartItem = CartItem::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'quantity' => 1,
                'price' => $book->price,
            ]);
            LogHelper::record('Cart', $cartItem->id, "Added book '{$book->name}' to cart");
        }

        SendCartReminderEmail::dispatch(Auth::id())->delay(now()->addHour());

        return redirect()->route('cart.index')->with('success', 'Book added to cart!');
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) abort(403);
        $bookName = $cartItem->book->name ?? 'Unknown';
        
        $cartItem->delete();

        LogHelper::record('Cart', $cartItem->id, "Removed book '{$bookName}' from cart");

        return back()->with('success', 'Item removed from cart');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) abort(403);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();
        
        LogHelper::record('Cart', $cartItem->id, "Updated quantity to {$cartItem->quantity} for book '{$cartItem->book->name}'");

        return back()->with('success', 'Quantity updated');
    }
}
