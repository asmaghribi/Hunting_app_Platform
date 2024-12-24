<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInDanger extends Mailable
{
    use Queueable, SerializesModels;

    public $position;

    public function __construct($position)
    {
        $this->position = $position;
    }

    public function build()
    {
        return $this->markdown('emails.user-in-danger')
                    ->with([
                        'position' => $this->position,
                    ]);
    }
}
