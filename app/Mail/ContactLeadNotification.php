<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactLeadNotification extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public ContactMessage $messageRecord)
    {
    }

    public function build(): self
    {
        return $this->subject('New Website Contact Lead: ' . ($this->messageRecord->subject ?: 'General Inquiry'))
            ->view('emails.contact-lead', [
                'lead' => $this->messageRecord,
            ]);
    }
}
