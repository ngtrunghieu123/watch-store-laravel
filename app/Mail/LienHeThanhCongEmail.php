<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class LienHeThanhCongEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public Request $lienhe;
    public function __construct(Request $request)
    {
        $this->lienhe = $request;
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'liên hệ tại ' . config('app.name', 'Laravel'),
        );
    }
    /**
     * Get the message envelope.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.lienhethanhcong',
            with: [
                'lienhe' => $this->lienhe,
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
