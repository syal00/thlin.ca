<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MediaFile extends Model
{
    protected $fillable = [
        'title',
        'original_name',
        'file_name',
        'file_path',
        'cloudinary_public_id',
        'file_type',
        'mime_type',
        'file_size',
        'description',
        'uploaded_by',
    ];

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }

        return route('media.public', $this);
    }

    public function fileIsAvailable(): bool
    {
        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return true;
        }

        return Storage::disk('public')->exists($this->file_path);
    }

    public function getFormattedSizeAttribute(): string
    {
        if (! $this->file_size) {
            return 'Unknown';
        }

        if ($this->file_size >= 1048576) {
            return round($this->file_size / 1048576, 2).' MB';
        }

        return round($this->file_size / 1024, 2).' KB';
    }
}
