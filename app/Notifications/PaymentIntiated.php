<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\VonageMessage;


class PaymentIntiated extends Notification
{
    // use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return $notifiable->prefers_sms ? ['vonage'] : ['mail'];
        return ['vonage', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $otp = $notifiable->trx_otp;
        $content = "Your one time password is {$otp}.DO NOT SHARE WITH ANYONE!";
        return (new MailMessage)
                    ->greeting('Hello!')
                    ->line($content);

    }

    /**
     * Get the vonage representation of the notification.
     */
    public function toVonage(object $notifiable): VonageMessage
    {
        $otp = $notifiable->trx_otp;

        $content = "Your one time password is {$otp}.DO NOT SHARE WITH ANYONE!";
        
        return (new VonageMessage)
                    ->content($content);

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
