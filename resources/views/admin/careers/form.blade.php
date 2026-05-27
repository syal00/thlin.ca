@extends('admin.layout')

@section('title', $career->exists ? 'Edit job' : 'Add job')

@section('content')
    <h1>{{ $career->exists ? 'Edit' : 'Add' }} job posting</h1>
    <div class="admin-card">
        <form method="post" action="{{ $career->exists ? route('admin.careers.update', $career) : route('admin.careers.store') }}">
            @csrf
            @if ($career->exists) @method('PUT') @endif
            <div class="form-group"><label for="title">Title</label><input id="title" name="title" value="{{ old('title', $career->title) }}" required></div>
            <div class="form-group"><label for="location">Location</label><input id="location" name="location" value="{{ old('location', $career->location) }}"></div>
            <div class="form-group"><label for="employment_type">Type</label><input id="employment_type" name="employment_type" value="{{ old('employment_type', $career->employment_type) }}"></div>
            <div class="form-group"><label for="posted_at">Posted</label><input type="date" id="posted_at" name="posted_at" value="{{ old('posted_at', $career->posted_at?->format('Y-m-d')) }}"></div>
            <div class="form-group"><label for="closes_at">Closes</label><input type="date" id="closes_at" name="closes_at" value="{{ old('closes_at', $career->closes_at?->format('Y-m-d')) }}"></div>
            <div class="form-group"><label for="body">Body (HTML)</label><textarea id="body" name="body" rows="12">{{ old('body', $career->body) }}</textarea></div>
            <div class="form-group"><label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $career->is_active))> Active</label></div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
