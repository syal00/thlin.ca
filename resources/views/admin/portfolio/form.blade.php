@extends('admin.layout')

@section('title', $item->exists ? 'Edit portfolio' : 'Add portfolio')
@section('page_title', $item->exists ? 'Edit portfolio item' : 'Add portfolio item')
@section('page_subtitle', 'Manage portfolio entries displayed on the public website.')

@section('content')
    <div class="admin-page-actions">
        <a href="{{ route('admin.portfolio.index') }}" class="btn btn-light">Back to portfolio</a>
    </div>

    <div class="admin-card">
        <form method="post" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.portfolio.update', $item) : route('admin.portfolio.store') }}">
            @csrf
            @if ($item->exists) @method('PUT') @endif

            <div class="form-group">
                <label class="form-label" for="title">Title</label>
                <input class="form-control" id="title" name="title" value="{{ old('title', $item->title) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="url">URL</label>
                <input class="form-control" type="url" id="url" name="url" value="{{ old('url', $item->url) }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="excerpt">Excerpt</label>
                <textarea class="form-control" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $item->excerpt) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="image_file">Image</label>
                @if ($item->image)
                    <div style="margin-bottom: 0.75rem;">
                        <img src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" alt="" style="max-width: 200px; border-radius: 12px;">
                    </div>
                @endif
                <input class="form-control" type="file" id="image_file" name="image_file" accept="image/jpeg,image/png,image/webp">
            </div>

            <div class="form-group">
                <label class="form-label" for="sort_order">Sort order</label>
                <input class="form-control" type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $item->sort_order) }}" min="0">
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="featured" value="1" @checked(old('featured', $item->featured))>
                    Featured on home page
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
