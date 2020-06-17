<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Daran\Models\Contact;

class ContactReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $contatto;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contatto)
    {
        $this->contatto = $contatto;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'),config('mail.from.name'))->subject('Nuovo contatto da web')->view('emails.contact-request');
    }
}
