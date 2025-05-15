<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AddOMC extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmations',
            from: 'xpfleet@gmail.com'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.omcEnquiry',
            with: [
                'url' => env('APP_URL'),
            ]
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


/*

public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmations',
            from: 'xpfleet@gmail.com', 
            replyTo: 'none'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'omcs.addOMC',
            width: [
                'name' => 'Eli Fuller',
                'email' => 'elijahfulle@gmail.com',
                'message' => 'Sup, welcome to Fleet XP',
            ]
        );
    }


*/