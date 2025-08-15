<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Book Request Confirmation</h2>

    <p>Hello {{ $user->name }},</p>

    <p>Your book request has been confirmed! Here are the details:</p>

    <ul>
        <li><strong>Request #:</strong> {{ $request->request_number ?? $request->id }}</li>
        <li><strong>Book:</strong> {{ $book->name }}</li>
        <li><strong>Start Date:</strong> {{ $request->start_date }}</li>
        <li><strong>Due Date:</strong> {{ $request->due_date }}</li>
        <li><strong>Status:</strong> {{ ucfirst($request->status) }}</li>
    </ul>

    @if($book->cover_url)
        <p><img src="{{ asset($book->cover_url) }}" alt="{{ $book->name }}" style="max-width:150px;"></p>
    @endif

    <p>Thank you!</p>
</body>
</html>
