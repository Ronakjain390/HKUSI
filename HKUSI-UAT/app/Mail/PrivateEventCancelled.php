<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PrivateEventCancelled extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mailInfo;

    /**
     * Create a new message instance.
     * Private Event Cancell Mail Created By Akash
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
        return  $this->subject('Cancellation of Your Scheduled Private Event – HKUSI')->view('emails.eventcancelled');
    }
}
 