<?php

namespace App\Support;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CloudinaryStorage
{
    public static function isConfigured(): bool
    {
        return filled(env('CLOUDINARY_URL'));
    }

    private static function client(): ?Cloudinary
    {
        if (! self::isConfigured()) {
            return null;
        }

        return new Cloudinary(env('CLOUDINARY_URL'));
    }

    /**
     * @return array{url: string, public_id: string|null, file_path: string}
     */
    public static function upload(UploadedFile $file, string $folder, string $resourceType = 'auto'): array
    {
        $client = self::client();

        if ($client) {
            $result = $client->uploadApi()->upload($file->getRealPath(), [
                'folder' => $folder,
                'resource_type' => $resourceType,
            ]);

            return [
                'url' => $result['secure_url'],
                'public_id' => $result['public_id'],
                'file_path' => $result['secure_url'],
            ];
        }

        $fileName = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs(trim($folder, '/'), $fileName, 'public');

        return [
            'url' => Storage::disk('public')->url($path),
            'public_id' => null,
            'file_path' => $path,
        ];
    }

    public static function destroy(?string $publicId, ?string $localPath = null, string $resourceType = 'image'): void
    {
        $client = self::client();

        if ($publicId && $client) {
            $client->uploadApi()->destroy($publicId, ['resource_type' => $resourceType]);

            return;
        }

        if ($localPath && ! str_starts_with($localPath, 'http') && Storage::disk('public')->exists($localPath)) {
            Storage::disk('public')->delete($localPath);
        }
    }
}
