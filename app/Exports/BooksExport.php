<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    
    protected $authorIds;
    protected $publisherId;
    protected $search;

    public function __construct($search = null, $publisherId = null, $authorIds = null)
    {
        $this->search = $search;
        $this->publisherId = $publisherId;
        $this->authorIds = $authorIds ? explode(',', $authorIds) : null;
    }

    public function collection()
    {
        $query = Book::with(['publisher', 'authors']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('isbn', 'like', "%{$this->search}%");
            });
        }

        if ($this->publisherId) {
            $query->where('publisher_id', $this->publisherId);
        }

        if ($this->authorIds) {
            $query->whereHas('authors', function ($q) {
                $q->whereIn('authors.id', $this->authorIds);
            });
        }

        return $query->get()->map(function ($book) {
            return [
                'ISBN' => $book->isbn,
                'Name' => $book->name,
                'Publisher' => $book->publisher->name ?? '-',
                'Authors' => $book->authors->pluck('name')->join(', '),
                'Price' => number_format($book->price, 2, ',', '.'),
            ];
        });
    }

    public function headings(): array
    {
        return ['ISBN', 'Name', 'Publisher', 'Authors', 'Price'];
    }
}

