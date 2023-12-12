<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Http\Request;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Support\Carbon;




class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(Request $request)
    {
        $this->link = $request->link;
        $this->email = $request->email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('ledjainc@gmail.com', 'LEDJA'),
            subject: 'PASSWORD RESET LINK',
        );
    }

    public function randomNumber($length)
    {
        $result = '';
        for($i = 0; $i < $length; $i++)
        {
            $result .= rand(0,9);
        }

        return $result;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $code = $this->randomNumber(8);
        $user = User::where('email', $this->email)->first();
        $token = $user->createToken('MyApp')->plainTextToken;

        // save email,token and code in password_resets_table
        $input = [
            'email' => $this->email,
            'code' => $code,
            'token' => $token,
            'created_at' => now(),
        ];

        $checkPasswordReset = PasswordResetToken::where('email', $this->email)->delete();

       /*  if($checkPasswordReset != null)
        {
            $checkPasswordReset->destroy();
        } */

        $passwordReset = PasswordResetToken::create($input);


        return new Content(
            view: 'emails.passwordreset',
            with: [
                'link' => $this->link,
                'code' => $code
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
