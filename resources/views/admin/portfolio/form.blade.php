@extends('admin.layout')

@section('title', $item->exists ? 'Edit portfolio' : 'Add portfolio')

@section('content')
    <h1>{{ $item->exists ? 'Edit' : 'Add' }} portfolio item</h1>
    <div class="admin-card">
        <form method="post" action="{{ $item->exists ? route('admin.portfolio.update', $item) : route('admin.portfolio.store') }}">
            @csrf
            @if ($item->exists) @method('PUT') @endif
            <div class="form-group"><label for="title">Title</label><input id="title" name="title" value="{{ old('title', $item->title) }}" required></div>
            <div class="form-group"><label for="url">URL</label><input type="url" id="url" name="url" value="{{ old('url', $item->url) }}"></div>
            <div class="form-group"><label for="excerpt">Excerpt</label><textarea id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $item->excerpt) }}</textarea></div>
            <div class="form-group"><label for="sort_order">Sort order</label><input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $item->sort_order) }}" min="0"></div>
            <div class="form-group"><label><input type="checkbox" name="featured" value="1" @checked(old('featured', $item->featured))> Featured on home page</label></div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
