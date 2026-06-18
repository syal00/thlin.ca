<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardMember;
use App\Models\Career;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\PortfolioItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InlineEditController extends Controller
{
    private const MODELS = [
        'page' => Page::class,
        'news' => NewsPost::class,
        'portfolio' => PortfolioItem::class,
        'career' => Career::class,
        'board' => BoardMember::class,
    ];

    /** @var array<string, list<string>> */
    private const ALLOWED_FIELDS = [
        'page' => ['title', 'hero_title', 'hero_subtitle', 'excerpt'],
        'news' => ['title', 'excerpt'],
        'portfolio' => ['title', 'excerpt'],
        'career' => ['title', 'location', 'employment_type'],
        'board' => ['name', 'role', 'bio'],
    ];

    private const IMAGE_FIELDS = [
        'portfolio' => ['image'],
    ];

    /** @var array<string, string> */
    private const FIELD_ALIASES = [
        'heading' => 'title',
        'description' => 'excerpt',
        'content' => 'body',
        'position' => 'role',
        'name' => 'name',
    ];

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'model' => ['required', 'string'],
            'id' => ['required', 'integer'],
            'field' => ['required', 'string'],
            'value' => ['nullable', 'string'],
        ]);

        $modelKey = $this->normalizeModelKey($validated['model']);
        $modelClass = self::MODELS[$modelKey] ?? null;

        if (! $modelClass) {
            return response()->json(['success' => false, 'message' => 'Invalid model.'], 403);
        }

        $field = $this->resolveField($modelKey, $validated['field']);

        if (! $this->isAllowedField($modelKey, $field)) {
            return response()->json(['success' => false, 'message' => 'This field cannot be edited inline.'], 403);
        }

        /** @var Model|null $record */
        $record = $modelClass::query()->find($validated['id']);

        if (! $record) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $value = $this->sanitizeValue($modelKey, $field, (string) ($validated['value'] ?? ''));

        $record->update([$field => $value === '' ? null : $value]);

        return response()->json([
            'success' => true,
            'value' => $value,
        ]);
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'model' => ['required', 'string'],
            'id' => ['required', 'integer'],
            'field' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $modelKey = $this->normalizeModelKey($validated['model']);
        $modelClass = self::MODELS[$modelKey] ?? null;

        if (! $modelClass) {
            return response()->json(['success' => false, 'message' => 'Invalid model.'], 403);
        }

        $allowedImages = self::IMAGE_FIELDS[$modelKey] ?? [];

        if (! in_array($validated['field'], $allowedImages, true)) {
            return response()->json(['success' => false, 'message' => 'Invalid image field.'], 403);
        }

        /** @var Model|null $record */
        $record = $modelClass::query()->find($validated['id']);

        if (! $record) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $path = $request->file('image')->store('uploads', 'public');
        $record->update([$validated['field'] => $path]);

        return response()->json([
            'success' => true,
            'url' => Storage::url($path),
        ]);
    }

    private function normalizeModelKey(string $model): string
    {
        return Str::of($model)->lower()->trim()->toString();
    }

    private function resolveField(string $modelKey, string $field): string
    {
        $field = Str::of($field)->snake()->toString();

        if ($field === 'description' && in_array($modelKey, ['portfolio', 'news', 'page'], true)) {
            return 'excerpt';
        }

        if ($field === 'position' && $modelKey === 'board') {
            return 'role';
        }

        return self::FIELD_ALIASES[$field] ?? $field;
    }

    private function isAllowedField(string $modelKey, string $field): bool
    {
        return in_array($field, self::ALLOWED_FIELDS[$modelKey] ?? [], true);
    }

    private function sanitizeValue(string $modelKey, string $field, string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        $value = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $value) ?? $value;
        $value = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $value) ?? $value;

        if (in_array($field, ['bio'], true)) {
            return strip_tags($value, '<p><br><strong><em><u><ul><ol><li><a>');
        }

        return strip_tags($value);
    }
}
