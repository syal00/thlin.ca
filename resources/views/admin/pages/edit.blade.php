@extends('admin.layout')

@section('title', 'Edit: '.$page->title)

@section('content')
    <h1>Edit: {{ $page->title }}</h1>
    <p>Slug: <code>{{ $page->slug }}</code> &middot; <a href="{{ $page->url() }}" target="_blank" rel="noopener">View on site</a></p>

    <div class="admin-card">
        <form method="post" action="{{ route('admin.pages.update', $page) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required>
            </div>

            <div class="form-group">
                <label for="meta_description">Meta description</label>
                <input type="text" id="meta_description" name="meta_description" value="{{ old('meta_description', $page->meta_description) }}" maxlength="500">
            </div>

            <div class="form-group">
                <label for="excerpt">Excerpt (used in search &amp; summaries)</label>
                <textarea id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $page->excerpt) }}</textarea>
            </div>

            <div class="form-group">
                <label for="body">Body (HTML allowed)</label>
                <textarea id="body" name="body" rows="16">{{ old('body', $page->body) }}</textarea>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $page->is_published))>
                    Published
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Save changes</button>
            <a href="{{ route('admin.pages.index') }}" class="btn" style="margin-left: 0.5rem;">Cancel</a>
        </form>
    </div>
@endsection
