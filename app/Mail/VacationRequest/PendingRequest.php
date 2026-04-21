<?php

namespace App\Mail\VacationRequest;

use App\Models\User;
use App\Models\VacationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendingRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public VacationRequest $vacation_request,
        public User $user,
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pending Request #' . $this->vacation_request->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.VacationRequest.pending_request',
            with: [
                'vacation_request' => $this->vacation_request,
                'user' => $this->user,
                'url' => route('filament.admin.resources.vacation-requests.edit', $this->vacation_request),
                'print' => route('print.vacation', $this->vacation_request->id)
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
