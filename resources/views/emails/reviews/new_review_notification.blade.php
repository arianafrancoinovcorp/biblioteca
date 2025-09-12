<x-mail::message>
# New Review Submitted

A new review was submitted by **{{ $review->user->name ?? 'Unknown User' }}**  
for the book **{{ $review->bookRequest->book->name ?? 'Unknown Book' }}**.

**Review content (pending approval):**
> {{ $review->content }}

<x-mail::button :url="route('admin.reviews.show', $review->id)">
View Review
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
