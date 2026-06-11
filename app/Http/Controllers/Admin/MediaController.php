<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(): View
    {
        $mediaFiles = MediaFile::latest()->paginate(15);

        return view('admin.media.index', compact('mediaFiles'));
    }

    public function create(): View
    {
        return view('admin.media.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $file = $request->file('file');

        $safeTitle = Str::slug($validated['title']);
        $fileName = $safeTitle.'-'.time().'.'.$file->getClientOriginalExtension();

        $path = $file->storeAs('uploads/files', $fileName, 'public');

        MediaFile::create([
            'title' => $validated['title'],
            'original_name' => $file->getClientOriginalName(),
            'file_name' => $fileName,
            'file_path' => $path,
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
        if (Storage::disk('public')->exists($mediaFile->file_path)) {
            Storage::disk('public')->delete($mediaFile->file_path);
        }

        $mediaFile->delete();

        return redirect()
            ->route('admin.media.index')
            ->with('success', 'File deleted successfully.');
    }
}
