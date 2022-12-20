<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAnswerMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $description;
    public string $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $name, string $description)
    {
        $this->description = $description;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->markdown('mail.send-answer-mail', ['description' => $this->description, 'name' => $this->name]);
    }
}
