<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\DeleteFileJob;
use App\Mail\SendEmailNotification;
use App\Models\File;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

use function compact;
use function env;
use function is_object;
use function now;
use function redirect;
use function response;

class FileController
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate(
            [
                'file' => 'required|file|mimes:pdf,docx|max:10240',
            ]
        );

        $file = $request->file('file');
        if (is_object($file)) {
            $filePath = $file->store('uploads');

            $fileRecord = File::create(
                [
                    File::NAME => $file->getClientOriginalName(),
                    File::PATH => $filePath,
                    File::SIZE => $file->getSize(),
                ]
            );
            $this->scheduleFileDeletion($fileRecord);

            return response()->json(
                [
                    'file' => [
                        'id' => $fileRecord->id,
                        'name' => $fileRecord->name,
                        'size' => $fileRecord->size,
                    ],
                ]
            );
        }

        return response()->json(
            [
                'error' => true,
            ]
        );
    }

    public function index(): View
    {
        $files = File::all();

        return \view('files.index', compact('files'));
    }

    public function delete(int $id): RedirectResponse
    {
        $file = File::findOrFail($id);

        if ($file->path) {
            Storage::delete($file->path);
        }
        $file->delete();

        Mail::to(env('MAIL_FROM_ADDRESS'))
            ->send(new SendEmailNotification($file));

        return redirect()->route('file.index');
    }

    private function scheduleFileDeletion(File $file)
    {
        $delay = now()->addMinutes();
        Queue::later($delay, new DeleteFileJob($file));
    }
}
