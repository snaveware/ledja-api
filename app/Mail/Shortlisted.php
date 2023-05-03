<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use Mail;

class Shortlisted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(Array $data)
    {
        $this->data = $data; 
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('ledjainc@gmail.com', 'LEDJA'),
            subject: 'INVITATION FOR INTERVIEW',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $fname = $this->data[0]['user']['basic_info_jobseeker']['fname'];
        $lname = $this->data[0]['user']['basic_info_jobseeker']['lname'];
        $job = $this->data[1]['job']['title'];
        $location = $this->data[1]['job']['location'];

        return new Content(
            view: 'emails.shortlisted',
            with: [
                'job' => $job,
                'location' => $location,
                'fname' => $fname,
                'lname' => $lname,
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
