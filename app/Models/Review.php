<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'requests_id',
        'user_id',
        'book_id',
        'content',
        'status',
        'rejection_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookRequest()
    {
        return $this->belongsTo(\App\Models\BookRequests::class, 'requests_id');
    }
}
