<p>Hello {{ $review->user->name }},</p>

<p>Your review for <strong>{{ $review->bookRequest->book->title }}</strong> was updated to: 
<strong>{{ ucfirst($review->status) }}</strong></p>

@if($review->status === 'rejected')
    <p>Reason: {{ $review->rejection_reason }}</p>
@endif
