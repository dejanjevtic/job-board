<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class HRMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $message)
    {
        $this->user = $user;
        $this->message = $message; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {  
        return $this->view('emails.hrUser')->with([
                        'user' => $this->user->name,
                        'poruka' => $this->message
                    ]);;
    }
}
