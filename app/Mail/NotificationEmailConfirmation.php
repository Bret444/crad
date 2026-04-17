<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationEmailConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public string $confirmUrl;

    public function __construct(string $confirmUrl)
    {
        $this->confirmUrl = $confirmUrl;
    }

    public function build()
    {
        return $this->subject('Confirm Your Notification Email - CRAD')
                    ->view('emails.notification_email_confirm');
    }
}
