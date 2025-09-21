<x-mail::message>
# Book Available!

The book **{{ $book->name }}** is now available for request.

<x-mail::button :url="route('books.show', $book->id)">
View Book
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
