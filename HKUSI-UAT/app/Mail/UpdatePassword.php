<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdatePassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mailInfo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailInfo)
    {
        $this->mailInfo = $mailInfo;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return  $this->subject('Password Updated – HKUSI Online Reservation Platform')->view('emails.updatepassword');
    }
}
