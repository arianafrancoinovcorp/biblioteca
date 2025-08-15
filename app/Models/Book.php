<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
