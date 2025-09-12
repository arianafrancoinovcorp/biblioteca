<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['isbn', 'name', 'publisher_id', 'bibliography', 'cover_image', 'price'];

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'author_book');
    }

    public function isAvailable()
    {
        return !$this->requests()
            ->whereNull('return_date')
            ->exists();
    }

    public function requests()
    {
        return $this->hasMany(BookRequests::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 'active');
    }

    public function relatedBooks($limit = 5)
    {
        $titleWords = $this->extractKeywords($this->name);
        $descWords  = $this->extractKeywords($this->description);

        $currentWords = array_merge(
            array_fill_keys($titleWords, 3),
            array_fill_keys($descWords, 1)
        );

        $books = Book::where('id', '!=', $this->id)->get();

        $booksWithScore = $books->map(function ($book) use ($currentWords) {
            $titleWordsOther = $this->extractKeywords($book->name);
            $descWordsOther  = $this->extractKeywords($book->description);

            $otherWords = array_merge(
                array_fill_keys($titleWordsOther, 3),
                array_fill_keys($descWordsOther, 1)
            );

            $score = 0;
            foreach ($otherWords as $word => $weight) {
                if (isset($currentWords[$word])) {
                    $score += $weight + $currentWords[$word];
                }
            }

            $book->score = $score;
            return $book;
        });

        $booksWithScore = $booksWithScore->filter(fn($b) => $b->score > 0);

        return $booksWithScore->sortByDesc('score')->take($limit);
    }

    private function extractKeywords($text)
    {
        $stopWords = [
            'a','an','the','and','or','but','of','in','on','with','at','by','for','to','from','is','are','was','were','be','been','being','as','that','this','these','those','it','its'
        ];

        $text = strtolower($text);
        $text = preg_replace('/[^\w\s]/', '', $text);
        $words = explode(' ', $text);

        // Remove stopwords and short words
        $words = array_filter($words, function($word) use ($stopWords) {
            return !in_array($word, $stopWords) && strlen($word) > 3;
        });

        return array_values($words);
    }
}
