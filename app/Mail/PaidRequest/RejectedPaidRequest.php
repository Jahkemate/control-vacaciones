<?php

namespace App\Mail\PaidRequest;

use App\Models\PaidRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RejectedPaidRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public PaidRequest $paid_request,
        public User $user,
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
            subject: 'Su Solicitud de Pago ha sido Rechazada #'. $this->paid_request->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
         view: 'emails.PaidRequest.approved_request',
            with: [
                'paid_request' => $this->paid_request,
                'user' => $this->user,
                'url' => route('detailsRejected', $this->paid_request->id),
                'app' => route('filament.admin.resources.paid-requests.index', $this->paid_request)
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
