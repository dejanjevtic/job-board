<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;
    public $jobs;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $job)
    {
          $this->user = $user;
          $this->job = $job;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.verifyUser')->with([
                        'title' => $this->job->title,
                        'description' => $this->job->description,
                        'token' => $this->user->verifyUser->token, 
                    ]);
    }
}
