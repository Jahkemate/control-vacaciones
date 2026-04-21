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

class ApprovedManagerPaidRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public PaidRequest $paid_request,
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
            subject: 'Paid request approved by manager' . $this->paid_request->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.PaidRequest.approved_manager_request',
            with: [
                'paid_request' => $this->paid_request,
                'user' => $this->user,
                'url' => route('filament.admin.resources.paid-requests.edit', $this->paid_request),
                'print' => route('print.vacation', $this->paid_request->id)
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
