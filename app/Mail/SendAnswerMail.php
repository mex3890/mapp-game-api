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
    public string $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $name, string $description, string $password)
    {
        $this->description = $description;
        $this->name = $name;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->markdown('mail.send-answer-mail', [
            'description' => $this->description,
            'name' => $this->name,
            'password' => $this->password
        ]);
    }
}
