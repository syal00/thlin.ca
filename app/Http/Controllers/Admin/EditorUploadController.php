<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\CloudinaryStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EditorUploadController extends Controller
{
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $upload = CloudinaryStorage::upload($request->file('file'), 'thlin/editor', 'image');

        return response()->json([
            'location' => $upload['url'],
        ]);
    }
}
