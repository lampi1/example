<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewsletterReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $lang;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email,$lang)
    {
        $this->email = $email;
        $this->lang = $lang;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'),config('mail.from.name'))->subject('Nuova iscrizione newsletter')->view('emails.newsletter-request');
    }
}
