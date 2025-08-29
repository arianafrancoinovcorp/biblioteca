<?php

namespace App\Http\Controllers;

use App\Models\Book;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    //latest 6 books
    public function index()
    {
        $latestBooks = Book::with('publisher', 'authors')
            ->whereNotNull('cover_image')
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', compact('latestBooks'));
    }
}
