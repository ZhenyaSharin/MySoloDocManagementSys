<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SystemMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $info = [
            'link' => $this->data['link'],
            'note' => $this->data['note'] ?? null,
            'user' => $this->data['user'] ?? null,
            'desc' => $this->data['desc'] ?? null,
            'user' => $this->data['user'] ?? null,
            'list' => $this->data['list'] ?? null,
            'refused' => $this->data['refused'] ?? null,
            'deadline' => $this->data['deadline'] ?? null,
        ];
        switch ($this->data['type']) {
            case 1:
                return $this->view('emails.agreement')
                    ->with($info);
            case 2:
                return $this->view('emails.assignment')
                    ->with($info);
            case 3:
                return $this->view('emails.refused')
                    ->with($info);
            case 4:
                return $this->view('emails.assignpersonal')
                    ->with($info);
            case 5:
                return $this->view('emails.acquaintance')
                    ->with($info);
            case 6:
                return $this->view('emails.newdeadline')
                    ->with($info);
            case 7:
                return $this->view('emails.agreestatistics')
                    ->with($info);
        }
    }
}
