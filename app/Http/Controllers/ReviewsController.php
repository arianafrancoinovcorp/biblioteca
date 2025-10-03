<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\BookRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewReviewNotification;
use App\Mail\ReviewStatusChanged;
use App\Models\User;
use App\Helpers\LogHelper;

class ReviewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Citizen creates the review 
     */
    public function store(Request $request, $requestId)
    {
        $bookRequest = BookRequests::findOrFail($requestId);

        if ($bookRequest->user_id !== Auth::id()) {
            abort(403, 'Access denied');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $review = Review::create([
            'requests_id' => $bookRequest->id,
            'user_id'     => Auth::id(),
            'book_id'     => $bookRequest->book_id,
            'content'     => $request->content,
            'status'      => 'suspended',
        ]);

        LogHelper::record('Reviews', $review->id, "User submitted a review for book request #{$bookRequest->id}");

        // Admin notification
        $adminEmails = User::where('role', 'admin')
            ->whereNotNull('email')
            ->pluck('email')
            ->toArray();

        if (!empty($adminEmails)) {
            // Mail::to($adminEmails)->send(new NewReviewNotification($review));
        }

        return redirect()->route('requests.show', $bookRequest->id)
            ->with('success', 'Your review was submitted and is pending approval!');
    }

    /**
     * List of reviews for admin panel.
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        $reviews = Review::with('user', 'bookRequest.book')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Admin can see review details
     */
    public function show($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        $review = Review::with('user', 'bookRequest.book')->findOrFail($id);

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Admin can approve/reject the review.
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        $review = Review::findOrFail($id);

        $request->validate([
            'status'            => 'required|in:active,rejected,suspended',
            'rejection_reason'  => 'nullable|string|max:500',
        ]);
        $oldStatus = $review->status;
        $review->status = $request->status;
        $review->rejection_reason = $request->status === 'rejected'
            ? $request->rejection_reason
            : null;
        $review->save();

        LogHelper::record('Reviews', $review->id, "Admin changed review status from '{$oldStatus}' to '{$review->status}'");

        // citizen notification
        //Mail::to($review->user->email)->send(new ReviewStatusChanged($review));

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    public function edit($id)
    {
        $review = Review::with('user', 'bookRequest.book')->findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        return view('admin.reviews.edit', compact('review'));
    }
}
