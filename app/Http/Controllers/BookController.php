<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use App\Exports\BooksExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Models\BookAvailabilityAlert;

class BookController extends Controller
{
    public function adminIndex(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $query = Book::with('publisher', 'authors');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('isbn', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('author_id')) {
            $query->whereHas('authors', function ($q) use ($request) {
                $q->where('id', $request->author_id);
            });
        }

        if ($request->filled('sort_by')) {
            $direction = $request->input('sort_direction', 'asc');
            $query->orderBy($request->sort_by, $direction);
        }

        $books = $query->paginate(10);
        $authors = Author::orderBy('name')->get(); 

        return view('admin.books.index', compact('books', 'authors'));
    }
    
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
    
            if (!$query->exists()) {
                app(\App\Services\GoogleBooksService::class)->importBooks($search);
    
                $query = Book::with('publisher', 'authors')
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            }
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
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
    
        $publishers = Publisher::orderBy('name')->get();
        $authors    = Author::orderBy('name')->get();
    
        return view('books.create', compact('publishers', 'authors'));
    }
    


    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'publisher_id' => 'required|exists:publishers,id',
            'price' => 'required|numeric',
            'bibliography' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'author_ids' => 'nullable|array',
            'author_ids.*' => 'exists:authors,id',
        ]);


        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('cover_images', 'public');
            $validated['cover_image'] = $path;
        }

        $book = Book::create($validated);

        // Associar autores (se houver)
        if (!empty($request->author_ids)) {
            $book->authors()->sync($request->author_ids);
        }

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load('reviews.user');
        
        $relatedBooks = $book->relatedBooks();
    
        return view('books.show', compact('book', 'relatedBooks'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $book = Book::findOrFail($id);
        $publishers = Publisher::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();

        return view('books.edit', compact('book', 'publishers', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $id,
            'publisher_id' => 'required|exists:publishers,id',
            'price' => 'required|numeric',
        ]);

        $book = Book::findOrFail($id);
        $book->update($validated);

        if ($request->filled('author_ids')) {
            $book->authors()->sync($request->author_ids);
        }

        return redirect()->route('books.index')->with('success', 'Book updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully');
    }
}
