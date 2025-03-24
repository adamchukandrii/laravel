<?php declare(strict_types=1);

namespace App\Jobs;

use App\Mail\SendEmailNotification;
use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

use function env;
use function now;

class DeleteFileJob implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly File $file
    ) {}

    public function handle(): void
    {
        if (Storage::exists($this->file->path)) {
            Storage::delete($this->file->path);
        }
        $this->file->deleted_at = now();
        $this->file->save();
        Mail::to(env('MAIL_FROM_ADDRESS'))
            ->send(new SendEmailNotification($this->file));
    }
}
