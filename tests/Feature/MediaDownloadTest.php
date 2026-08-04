<?php

namespace Tests\Feature;

use App\Models\MediaFile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaDownloadTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_media_download_route_serves_uploaded_pdf(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('thlin/media/sample.pdf', '%PDF-1.4 sample');

        $admin = User::firstOrFail();
        $mediaFile = MediaFile::create([
            'title' => 'Sample PDF',
            'original_name' => 'sample.pdf',
            'file_name' => 'sample.pdf',
            'file_path' => 'thlin/media/sample.pdf',
            'file_type' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 128,
            'uploaded_by' => $admin->id,
        ]);

        $this->get(route('media.public', $mediaFile))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_media_download_route_returns_not_found_for_missing_file(): void
    {
        Storage::fake('public');

        $admin = User::firstOrFail();
        $mediaFile = MediaFile::create([
            'title' => 'Missing PDF',
            'original_name' => 'missing.pdf',
            'file_name' => 'missing.pdf',
            'file_path' => 'thlin/media/missing.pdf',
            'file_type' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 128,
            'uploaded_by' => $admin->id,
        ]);

        $this->get(route('media.public', $mediaFile))
            ->assertNotFound()
            ->assertSee('PDF not available on this server')
            ->assertSee('Uploaded Files');
    }

    public function test_public_storage_route_serves_editor_uploads(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('thlin/editor/chart.png', 'image-bytes');

        $this->get('/storage/thlin/editor/chart.png')
            ->assertOk();
    }
}
