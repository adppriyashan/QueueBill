<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceStatementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bodyText;
    public $subjectText;
    public $pdfData;
    public $pdfFilename;

    /**
     * Create a new message instance.
     */
    public function __construct($subjectText, $bodyText, $pdfData, $pdfFilename)
    {
        $this->subjectText = $subjectText;
        $this->bodyText = $bodyText;
        $this->pdfData = $pdfData;
        $this->pdfFilename = $pdfFilename;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectText,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice_statement',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        if ($this->pdfData) {
            return [
                Attachment::fromData(fn () => $this->pdfData, $this->pdfFilename)
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
