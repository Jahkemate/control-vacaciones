<?php

namespace App\Mail\CompensationRequest;

use App\Models\RequestForCompensation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RejectedCompensationRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public RequestForCompensation $compensation_request,
        public User $user
    )
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
             subject: 'Su Solicitu por Compensacion ha sido Rechazada #' . $this->compensation_request->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
             view: 'emails.CompensationRequest.approved_manager_request',
            with: [
                'compensation_request' => $this->compensation_request,
                'user' => $this->user,
                'url' => route('detailsRejected', $this->compensation_request->id),
                'app' => route('filament.admin.resources.paid-requests.index', $this->compensation_request)
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
