<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditorUploadController extends Controller
{
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $file = $request->file('file');

        $fileName = Str::uuid().'.'.$file->getClientOriginalExtension();

        $path = $file->storeAs('uploads/images', $fileName, 'public');

        return response()->json([
            'location' => Storage::url($path),
        ]);
    }
}
