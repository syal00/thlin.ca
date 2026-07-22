<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\CloudinaryStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class EditorUploadController extends Controller
{
    public function uploadImage(Request $request): JsonResponse
    {
        try {
            // `mimes` only checks the filename extension, which pasted/blob
            // uploads from the editor don't reliably have. `mimetypes`
            // checks the actual file content, so pasted screenshots and
            // drag-dropped files are validated correctly either way.
            $request->validate([
                'file' => [
                    'required',
                    'image',
                    'mimetypes:image/jpeg,image/png,image/webp,image/gif',
                    'max:5120',
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->errors()['file'][0] ?? 'That file could not be uploaded.',
            ], 422);
        }

        try {
            $upload = CloudinaryStorage::upload($request->file('file'), 'thlin/editor', 'image');
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Image upload failed. Please try again.',
            ], 500);
        }

        return response()->json([
            'location' => $upload['url'],
        ]);
    }
}
