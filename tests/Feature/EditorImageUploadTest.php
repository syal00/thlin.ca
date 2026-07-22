<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EditorImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_guest_cannot_upload_editor_images(): void
    {
        $this->post(route('admin.editor.upload-image'), [
            'file' => UploadedFile::fake()->image('photo.png'),
        ])->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_upload_a_pasted_image_with_no_filename_extension(): void
    {
        Storage::fake('public');
        $admin = User::firstOrFail();

        // A pasted screenshot from TinyMCE arrives as a Blob named e.g.
        // "blob" (no extension), but the browser always sends the Blob's
        // real MIME type as the multipart Content-Type. This builds a real
        // UploadedFile the same way to prove the `mimetypes` (content-based)
        // check accepts it where the old `mimes` (extension-based) check
        // would have silently rejected it.
        $pngBytes = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk'
            .'+A8AAQUBAScY42YAAAAASUVORK5CYII='
        );
        $tmpPath = tempnam(sys_get_temp_dir(), 'thlin_paste_');
        file_put_contents($tmpPath, $pngBytes);

        $file = new UploadedFile($tmpPath, 'blob', 'image/png', null, true);

        $response = $this->actingAs($admin)
            ->post(route('admin.editor.upload-image'), ['file' => $file]);

        $response->assertOk()->assertJsonStructure(['location']);
    }

    public function test_upload_rejects_non_image_files_with_a_json_error(): void
    {
        $admin = User::firstOrFail();

        $file = UploadedFile::fake()->create('not-an-image.txt', 10, 'text/plain');

        $response = $this->actingAs($admin)
            ->post(route('admin.editor.upload-image'), ['file' => $file]);

        $response->assertStatus(422)->assertJsonStructure(['message']);
    }

    public function test_upload_rejects_oversized_images(): void
    {
        $admin = User::firstOrFail();

        $file = UploadedFile::fake()->image('huge.png')->size(6000);

        $response = $this->actingAs($admin)
            ->post(route('admin.editor.upload-image'), ['file' => $file]);

        $response->assertStatus(422)->assertJsonStructure(['message']);
    }
}
