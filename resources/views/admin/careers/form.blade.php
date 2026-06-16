@extends('admin.layout')

@section('title', $career->exists ? 'Edit job' : 'Add job')
@section('page_title', $career->exists ? 'Edit job posting' : 'Add job posting')
@section('page_subtitle', 'Manage career listings shown on the public careers page.')

@section('content')
    <div class="admin-page-actions">
        <a href="{{ route('admin.careers.index') }}" class="btn btn-light">Back to careers</a>
    </div>

    <div class="admin-card">
        <form method="post" action="{{ $career->exists ? route('admin.careers.update', $career) : route('admin.careers.store') }}">
            @csrf
            @if ($career->exists) @method('PUT') @endif

            <div class="form-group">
                <label class="form-label" for="title">Title</label>
                <input class="form-control" id="title" name="title" value="{{ old('title', $career->title) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="location">Location</label>
                <input class="form-control" id="location" name="location" value="{{ old('location', $career->location) }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="employment_type">Employment type</label>
                <input class="form-control" id="employment_type" name="employment_type" value="{{ old('employment_type', $career->employment_type) }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="posted_at">Posted date</label>
                <input class="form-control" type="date" id="posted_at" name="posted_at" value="{{ old('posted_at', $career->posted_at?->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="closes_at">Closing date</label>
                <input class="form-control" type="date" id="closes_at" name="closes_at" value="{{ old('closes_at', $career->closes_at?->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="body">Body (HTML)</label>
                <textarea class="form-control" id="body" name="body" rows="12">{{ old('body', $career->body) }}</textarea>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $career->is_active))>
                    Active
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
