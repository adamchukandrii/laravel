<?php declare(strict_types=1);

namespace App\Mail;

use App\Models\File;
use Illuminate\Mail\Mailable;

class SendEmailNotification extends Mailable
{
    public function __construct(
        private readonly File $file
    ) {}

    public function build()
    {
        return $this->view('emails.delete')
            ->with(
                [
                    'filename' => $this->file->name,
                ]
            );
    }
}
