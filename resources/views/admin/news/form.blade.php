@extends('admin.layout')

@section('title', $post->exists ? 'Edit news' : 'Add news')

@section('content')
    <h1>{{ $post->exists ? 'Edit' : 'Add' }} news post</h1>
    <div class="admin-card">
        <form method="post" action="{{ $post->exists ? route('admin.news.update', $post) : route('admin.news.store') }}">
            @csrf
            @if ($post->exists) @method('PUT') @endif
            <div class="form-group"><label for="title">Title</label><input id="title" name="title" value="{{ old('title', $post->title) }}" required></div>
            <div class="form-group"><label for="published_at">Published date</label><input type="date" id="published_at" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d')) }}"></div>
            <div class="form-group"><label for="location">Location</label><input id="location" name="location" value="{{ old('location', $post->location) }}"></div>
            <div class="form-group"><label for="excerpt">Excerpt</label><textarea id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea></div>
            <div class="form-group"><label for="body">Body (HTML)</label><textarea id="body" name="body" rows="12">{{ old('body', $post->body) }}</textarea></div>
            <div class="form-group"><label><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published))> Published</label></div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
