<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendCartReminderEmail;

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
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'quantity' => 1,
                'price' => $book->price,
            ]);
        }

        SendCartReminderEmail::dispatch(Auth::id())->delay(now()->addHour());

        return redirect()->route('cart.index')->with('success', 'Livro adicionado ao carrinho!');
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) abort(403);
        $cartItem->delete();

        return back()->with('success', 'Item removido do carrinho');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) abort(403);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return back()->with('success', 'Quantidade atualizada');
    }
}
