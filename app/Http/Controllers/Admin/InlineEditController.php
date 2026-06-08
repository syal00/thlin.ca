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

class InlineEditController extends Controller
{
    private const MODELS = [
        'page' => Page::class,
        'news' => NewsPost::class,
        'portfolio' => PortfolioItem::class,
        'career' => Career::class,
        'board' => BoardMember::class,
    ];

    private const ALLOWED_FIELDS = [
        'title',
        'heading',
        'body',
        'description',
        'content',
        'name',
        'position',
        'excerpt',
        'role',
        'bio',
    ];

    private const IMAGE_FIELDS = [
        'image',
        'photo',
    ];

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'model' => ['required', 'string'],
            'id' => ['required', 'integer'],
            'field' => ['required', 'string'],
            'value' => ['required', 'string'],
        ]);

        $modelClass = self::MODELS[$validated['model']] ?? null;
        if (! $modelClass) {
            return response()->json(['success' => false, 'message' => 'Invalid model.'], 422);
        }

        $field = $this->resolveField($validated['model'], $validated['field']);
        if (! in_array($field, self::ALLOWED_FIELDS, true)) {
            return response()->json(['success' => false, 'message' => 'Invalid field.'], 422);
        }

        /** @var Model|null $record */
        $record = $modelClass::query()->find($validated['id']);
        if (! $record) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $record->update([$field => $validated['value']]);

        return response()->json(['success' => true]);
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'model' => ['required', 'string'],
            'id' => ['required', 'integer'],
            'field' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $modelClass = self::MODELS[$validated['model']] ?? null;
        if (! $modelClass) {
            return response()->json(['success' => false, 'message' => 'Invalid model.'], 422);
        }

        if (! in_array($validated['field'], self::IMAGE_FIELDS, true)) {
            return response()->json(['success' => false, 'message' => 'Invalid field.'], 422);
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
            'url' => Storage::disk('public')->url($path),
        ]);
    }

    private function resolveField(string $model, string $field): string
    {
        if ($field === 'description' && in_array($model, ['portfolio', 'news', 'page'], true)) {
            return 'excerpt';
        }

        if ($field === 'heading') {
            return 'title';
        }

        if ($field === 'content') {
            return 'body';
        }

        if ($field === 'position' && $model === 'board') {
            return 'role';
        }

        return $field;
    }
}
