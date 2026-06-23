<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardMember;
use App\Models\Career;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\PortfolioItem;
use App\Models\SiteSetting;
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
        'sitesetting' => SiteSetting::class,
    ];

    /** @var array<string, list<string>> */
    private const ALLOWED_FIELDS = [
        'page' => ['title', 'hero_title', 'hero_subtitle', 'body', 'excerpt', 'navigation_label', 'meta_description'],
        'sitesetting' => ['value'],
        'news' => ['title', 'excerpt', 'body'],
        'portfolio' => ['title', 'excerpt'],
        'career' => ['title', 'location', 'employment_type', 'body'],
        'board' => ['name', 'role', 'bio'],
    ];

    /** @var array<string, list<string>> */
    private const RICH_TEXT_FIELDS = [
        'page' => ['body', 'hero_subtitle', 'excerpt'],
        'sitesetting' => ['value'],
        'news' => ['body', 'excerpt'],
        'portfolio' => ['excerpt'],
        'career' => ['body'],
        'board' => ['bio'],
    ];

    private const IMAGE_FIELDS = ['portfolio' => ['image']];

    /** @var array<string, string> */
    private const FIELD_ALIASES = [
        'heading' => 'title',
        'description' => 'excerpt',
        'summary' => 'excerpt',
        'content' => 'body',
        'position' => 'role',
        'intro_text' => 'excerpt',
        'cta_text' => 'excerpt',
    ];

    /** @var array<string, int> */
    private const MAX_LENGTHS = [
        'title' => 255, 'hero_title' => 255, 'navigation_label' => 120,
        'name' => 255, 'role' => 255, 'location' => 255, 'employment_type' => 120,
        'value' => 10000, 'excerpt' => 2000, 'hero_subtitle' => 2000,
        'meta_description' => 500, 'bio' => 5000, 'body' => 50000,
    ];

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'model' => ['required', 'string'],
            'id' => ['nullable', 'integer'],
            'key' => ['nullable', 'string', 'max:120'],
            'field' => ['required', 'string'],
            'value' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:text,textarea,richtext'],
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

        if ($modelKey === 'sitesetting') {
            return $this->updateSiteSetting($validated, $field);
        }

        if (empty($validated['id'])) {
            return response()->json(['success' => false, 'message' => 'Record id is required.'], 422);
        }

        /** @var Model|null $record */
        $record = $modelClass::query()->find($validated['id']);

        if (! $record) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $value = $this->sanitizeValue(
            $modelKey,
            $field,
            (string) ($validated['value'] ?? ''),
            null,
            $validated['type'] ?? null
        );

        $changes = [$field => $value === '' ? null : $value];

        if ($record instanceof Page) {
            $changes['updated_by'] = $request->user()->id;
        }

        $record->update($changes);

        return response()->json(['success' => true, 'value' => $value]);
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

        if (! $modelClass || ! in_array($validated['field'], self::IMAGE_FIELDS[$modelKey] ?? [], true)) {
            return response()->json(['success' => false, 'message' => 'Invalid image field.'], 403);
        }

        /** @var Model|null $record */
        $record = $modelClass::query()->find($validated['id']);

        if (! $record) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $path = $request->file('image')->store('uploads', 'public');
        $record->update([$validated['field'] => $path]);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return response()->json(['success' => true, 'url' => $disk->url($path)]);
    }

    /** @param array<string, mixed> $validated */
    private function updateSiteSetting(array $validated, string $field): JsonResponse
    {
        $key = trim((string) ($validated['key'] ?? ''));

        if ($key === '' || ! preg_match('/^[a-z0-9_]+$/', $key)) {
            return response()->json(['success' => false, 'message' => 'Invalid setting key.'], 422);
        }

        $setting = SiteSetting::query()->where('key', $key)->first();

        if (! $setting) {
            return response()->json(['success' => false, 'message' => 'Unknown setting key.'], 403);
        }

        $value = $this->sanitizeValue(
            'sitesetting',
            $field,
            (string) ($validated['value'] ?? ''),
            $setting->type,
            $validated['type'] ?? null
        );
        $setting->update(['value' => $value === '' ? null : $value]);

        return response()->json(['success' => true, 'value' => $value]);
    }

    private function normalizeModelKey(string $model): string
    {
        return Str::of($model)->lower()->trim()->replace(' ', '')->toString();
    }

    private function resolveField(string $modelKey, string $field): string
    {
        $field = Str::of($field)->snake()->toString();

        if ($field === 'description' && in_array($modelKey, ['portfolio', 'news', 'page'], true)) {
            return 'excerpt';
        }

        if ($field === 'summary' && in_array($modelKey, ['career', 'portfolio'], true)) {
            return $modelKey === 'career' ? 'body' : 'excerpt';
        }

        if ($field === 'position' && $modelKey === 'board') {
            return 'role';
        }

        if ($field === 'title' && $modelKey === 'board') {
            return 'role';
        }

        return self::FIELD_ALIASES[$field] ?? $field;
    }

    private function isAllowedField(string $modelKey, string $field): bool
    {
        return in_array($field, self::ALLOWED_FIELDS[$modelKey] ?? [], true);
    }

    private function sanitizeValue(
        string $modelKey,
        string $field,
        string $value,
        ?string $settingType = null,
        ?string $requestType = null
    ): string {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        $value = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $value) ?? $value;
        $value = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $value) ?? $value;

        if ($this->isRichTextField($modelKey, $field, $settingType, $requestType)) {
            $value = $this->sanitizeRichText($value);
        } else {
            $value = strip_tags($value);
        }

        return Str::limit($value, self::MAX_LENGTHS[$field] ?? 1000, '');
    }

    private function isRichTextField(
        string $modelKey,
        string $field,
        ?string $settingType = null,
        ?string $requestType = null
    ): bool {
        if (in_array($requestType, ['richtext', 'textarea'], true)) {
            return in_array($field, self::RICH_TEXT_FIELDS[$modelKey] ?? [], true);
        }

        if ($modelKey === 'sitesetting' && $field === 'value') {
            return in_array($settingType, ['richtext', 'textarea'], true);
        }

        return in_array($field, self::RICH_TEXT_FIELDS[$modelKey] ?? [], true);
    }

    private function sanitizeRichText(string $value): string
    {
        $value = preg_replace('/<(iframe|object|embed|form|input|button)\b[^>]*>.*?<\/\1>/is', '', $value) ?? $value;
        $value = preg_replace('/<(iframe|object|embed|form|input|button)\b[^>]*\/?>/i', '', $value) ?? $value;
        $value = preg_replace('/\son\w+\s*=\s*(["\']).*?\1/i', '', $value) ?? $value;
        $value = preg_replace('/\shref\s*=\s*(["\'])\s*javascript:[^"\']*\1/i', ' href="#"', $value) ?? $value;

        $allowed = '<p><br><strong><b><em><i><u><s><strike><del><span><h2><h3><h4><ul><ol><li><a><table><thead><tbody><tr><th><td>';
        $value = strip_tags($value, $allowed);

        $value = preg_replace_callback('/<(p|span|h2|h3|h4|th|td)([^>]*)>/i', function (array $matches): string {
            $tag = strtolower($matches[1]);
            $attrs = $matches[2];

            if (! preg_match('/\sstyle\s*=\s*(["\'])(.*?)\1/i', $attrs, $styleMatch)) {
                return '<'.$tag.'>';
            }

            $safeStyle = $this->sanitizeInlineStyle($styleMatch[2]);

            return $safeStyle !== '' ? '<'.$tag.' style="'.$safeStyle.'">' : '<'.$tag.'>';
        }, $value) ?? $value;

        // TODO: Install HTMLPurifier for stronger rich-text sanitization.

        return $value;
    }

    private function sanitizeInlineStyle(string $style): string
    {
        $allowedProperties = ['color', 'background-color', 'text-align', 'font-size'];
        $safe = [];

        foreach (explode(';', $style) as $declaration) {
            $declaration = trim($declaration);
            if ($declaration === '' || ! str_contains($declaration, ':')) {
                continue;
            }

            [$property, $propertyValue] = array_map('trim', explode(':', $declaration, 2));
            $property = strtolower($property);

            if (! in_array($property, $allowedProperties, true)) {
                continue;
            }

            if (preg_match('/(expression|javascript:|url\s*\()/i', $propertyValue)) {
                continue;
            }

            if ($property === 'font-size' && ! preg_match('/^\d{1,2}px$/', $propertyValue)) {
                continue;
            }

            $safe[] = $property.': '.$propertyValue;
        }

        return implode('; ', $safe);
    }
}
