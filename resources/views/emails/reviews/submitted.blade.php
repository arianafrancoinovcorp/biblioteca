<p>A new review was submitted by {{ $review->user->name }}.</p>
<p>Book: {{ $review->bookRequest->book->title }}</p>
<p><a href="{{ route('admin.reviews.show', $review->id) }}">View Review</a></p>
