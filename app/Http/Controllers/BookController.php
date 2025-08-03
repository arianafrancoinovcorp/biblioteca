<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use App\Exports\BooksExport;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::with('publisher', 'authors');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('publisher_id')) {
            $query->where('publisher_id', $request->publisher_id);
        }

        if ($request->filled('author_id')) {
            $authorIds = $request->author_id;
            $query->whereHas('authors', function ($q) use ($authorIds) {
                $q->whereIn('authors.id', (array)$authorIds);
            });
        }

        $allowedSorts = ['name', 'isbn', 'price'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $allowedSorts)) {
            $direction = $request->get('sort_direction', 'asc');
            $query->orderBy($request->sort_by, $direction);
        } else {
            $query->latest();
        }

        $books = $query->simplePaginate(10)->appends($request->query());

        $publishers = Publisher::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();

        return view('books.index', compact('books', 'publishers', 'authors'));
    }

    public function export(Request $request)
    {
        try {
            $search = $request->query('search');
            $publisherId = $request->query('publisher_id');
            $authorIds = $request->query('author_id');
    
            return Excel::download(new BooksExport($search, $publisherId, $authorIds), 'books.xlsx');
        } catch (\Exception $e) {
            dd('Export failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
