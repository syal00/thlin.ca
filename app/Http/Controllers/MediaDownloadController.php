<?php

namespace App\Http\Controllers;

use App\Models\MediaFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaDownloadController extends Controller
{
    public function show(MediaFile $mediaFile): StreamedResponse|RedirectResponse|Response
    {
        if (str_starts_with($mediaFile->file_path, 'http://') || str_starts_with($mediaFile->file_path, 'https://')) {
            return redirect()->away($mediaFile->file_path);
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($mediaFile->file_path)) {
            return response()->view('media.unavailable', [
                'mediaFile' => $mediaFile,
            ], 404);
        }

        return $disk->response(
            $mediaFile->file_path,
            $mediaFile->original_name ?: $mediaFile->file_name,
            ['Content-Type' => $mediaFile->mime_type ?: 'application/pdf']
        );
    }
}
