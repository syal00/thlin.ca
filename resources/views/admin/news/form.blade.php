@extends('admin.layout')

@section('title', $post->exists ? 'Edit news' : 'Add news')
@section('page_title', $post->exists ? 'Edit news post' : 'Add news post')
@section('page_subtitle', 'Create or update a news article for the public website.')

@section('content')
    <div class="admin-page-actions">
        <a href="{{ route('admin.news.index') }}" class="btn btn-light">Back to news</a>
    </div>

    <div class="admin-card">
        <form method="post" action="{{ $post->exists ? route('admin.news.update', $post) : route('admin.news.store') }}">
            @csrf
            @if ($post->exists) @method('PUT') @endif

            <div class="form-group">
                <label class="form-label" for="title">Title</label>
                <input class="form-control" id="title" name="title" value="{{ old('title', $post->title) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="published_at">Published date</label>
                <input class="form-control" type="date" id="published_at" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="location">Location</label>
                <input class="form-control" id="location" name="location" value="{{ old('location', $post->location) }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="excerpt">Excerpt</label>
                <textarea class="form-control" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="body">Body (HTML)</label>
                <textarea class="form-control" id="body" name="body" rows="12">{{ old('body', $post->body) }}</textarea>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published))>
                    Published
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
