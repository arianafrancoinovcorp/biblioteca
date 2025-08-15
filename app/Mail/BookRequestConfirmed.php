<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\BookRequests;

class BookRequestConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $book;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(BookRequests $request)
    {
        $this->request = $request;
        $this->book = $request->book;
        $this->user = $request->user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Book Request Confirmed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.book-request-confirmed',
            with: [
                'request' => $this->request,
                'book' => $this->book,
                'user' => $this->user,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
