<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use App\Support\CloudinaryStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(): View
    {
        $mediaFiles = MediaFile::latest()->paginate(12);

        return view('admin.media.index', [
            'mediaFiles' => $mediaFiles,
            'persistentStorageConfigured' => CloudinaryStorage::isConfigured(),
            'isServerlessDeploy' => filled(env('VERCEL')) || filled(env('VERCEL_ENV')),
            'hasUnavailableFiles' => $mediaFiles->contains(fn (MediaFile $file) => ! $file->fileIsAvailable()),
        ]);
    }

    public function create(): View
    {
        return view('admin.media.create', [
            'persistentStorageConfigured' => CloudinaryStorage::isConfigured(),
            'isServerlessDeploy' => filled(env('VERCEL')) || filled(env('VERCEL_ENV')),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $file = $request->file('file');
        $upload = CloudinaryStorage::upload($file, 'thlin/media', 'raw');

        MediaFile::create([
            'title' => $validated['title'],
            'original_name' => $file->getClientOriginalName(),
            'file_name' => Str::slug($validated['title']).'-'.time().'.'.$file->getClientOriginalExtension(),
            'file_path' => $upload['file_path'],
            'cloudinary_public_id' => $upload['public_id'],
            'file_type' => 'pdf',
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'description' => $validated['description'] ?? null,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.media.index')
            ->with('success', 'PDF uploaded successfully.');
    }

    public function destroy(MediaFile $mediaFile): RedirectResponse
    {
        CloudinaryStorage::destroy(
            $mediaFile->cloudinary_public_id,
            $mediaFile->file_path,
            'raw'
        );

        $mediaFile->delete();

        return redirect()
            ->route('admin.media.index')
            ->with('success', 'File deleted successfully.');
    }
}
