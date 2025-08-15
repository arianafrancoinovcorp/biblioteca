<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequests extends Model
{
    use HasFactory;

    protected $table = 'requests';
    
    protected $fillable = [
        'request_number',
        'book_id',
        'user_id',
        'status',
        'start_date',
        'due_date',
        'return_date',
        'photo',
        'notes',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($request) {
            if (empty($request->request_number)) {
                $max = static::max('request_number') ?? 0;
                $request->request_number = $max + 1;
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public static function hasReachedLimit($userId)
    {
        return self::where('user_id', $userId)
            ->whereIn('status', ['pending', 'active'])
            ->count() >= 3;
    }

    public function daysSinceStart()
    {
        return now()->diffInDays($this->start_date);
    }

    public function daysUntilDue()
    {
        return now()->diffInDays($this->due_date, false);
    }
}
