<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookRequests;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookRequestConfirmed;
use App\Models\Review;
use Carbon\Carbon;

class RequestsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->is_admin) {
            $requests = BookRequests::with('book', 'user')
                ->orderByDesc('start_date')
                ->paginate(10);
        } else {
            $requests = BookRequests::with('book')
                ->where('user_id', Auth::id())
                ->orderByDesc('start_date')
                ->paginate(10);
        }

        $activeRequestsCount = BookRequests::where('status', 'active')->count();
        $last30DaysCount = BookRequests::where('start_date', '>=', Carbon::now()->subDays(30))->count();
        $returnedTodayCount = BookRequests::where('status', 'returned')
            ->whereDate('return_date', Carbon::today())
            ->count();

        return view('requests.index', compact('requests', 'activeRequestsCount', 'last30DaysCount', 'returnedTodayCount'));
    }

    public function create(Book $book)
    {
        return view('requests.create', compact('book'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'photo' => 'nullable|image|max:2048',
        ]);

        if (BookRequests::hasReachedLimit(auth()->id())) {
            return redirect()->back()->withErrors('You already have 3 active book requests.');
        }

        $data = [
            'book_id' => $request->book_id,
            'user_id' => auth()->id(),
            'status' => 'active',
            'notes' => $request->notes,
            'start_date' => now(),
            'due_date' => now()->addDays(5),
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('request_photos', 'public');
        }

        $bookRequest = BookRequests::create($data);
        $bookRequest->load('user', 'book');

        Mail::to($bookRequest->user->email)
            ->send(new BookRequestConfirmed($bookRequest));

        $adminEmails = User::where('is_admin', 1)
            ->whereNotNull('email')
            ->pluck('email')
            ->toArray();

        if (!empty($adminEmails)) {
            Mail::bcc($adminEmails)
                ->send(new BookRequestConfirmed($bookRequest));
        }

        return redirect()->route('requests.index')->with('success', 'Book requested successfully!');
    }

    public function show($id)
    {
        $bookRequest = BookRequests::with('book', 'user')->findOrFail($id);

        if (!Auth::user()->is_admin && $bookRequest->user_id !== Auth::id()) {
            abort(403, 'Access denied');
        }

        $existingReview = Review::where('requests_id', $bookRequest->id)
            ->where('user_id', Auth::id())
            ->first();

        $canReview = (
            $bookRequest->status === 'returned' &&
            $bookRequest->user_id === Auth::id() &&
            !$existingReview
        );

        return view('requests.show', [
            'request' => $bookRequest,
            'canReview' => $canReview,
        ]);
    }

    public function update(Request $request, $id)
    {

        if (!Auth::user()->is_admin) {
            abort(403, 'Access denied');
        }

        $bookRequest = BookRequests::findOrFail($id);
        $bookRequest->status = 'returned';
        $bookRequest->return_date = now();
        $bookRequest->save();

        return redirect()->route('requests.show', $bookRequest->id)
            ->with('success', 'Please leave a review.');
    }

    public function returnBook(BookRequests $bookRequest)
    {
        if (!Auth::user()->is_admin && $bookRequest->user_id !== Auth::id()) {
            abort(403, 'Access denied');
        }

        $bookRequest->status = 'returned';
        $bookRequest->return_date = now();
        $bookRequest->save();

        return redirect()->route('requests.show', $bookRequest->id)
            ->with('success', 'Please leave a review.');
    }

    public function userHistory($userId)
    {
        $user = User::findOrFail($userId);
        $requests = BookRequests::where('user_id', $userId)->with('book')->get();
        return view('requests.user_history', compact('user', 'requests'));
    }

    public function bookHistory($bookId)
    {
        $book = Book::findOrFail($bookId);
        $requests = BookRequests::where('book_id', $bookId)->with('user')->get();
        return view('requests.book_history', compact('book', 'requests'));
    }
}
