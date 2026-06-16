@extends('admin.layout')

@section('title', $member->exists ? 'Edit board member' : 'Add board member')
@section('page_title', $member->exists ? 'Edit board member' : 'Add board member')
@section('page_subtitle', 'Manage board of directors profiles on the public site.')

@section('content')
    <div class="admin-page-actions">
        <a href="{{ route('admin.board.index') }}" class="btn btn-light">Back to board</a>
    </div>

    <div class="admin-card">
        <form method="post" action="{{ $member->exists ? route('admin.board.update', $member) : route('admin.board.store') }}">
            @csrf
            @if ($member->exists) @method('PUT') @endif

            <div class="form-group">
                <label class="form-label" for="name">Name</label>
                <input class="form-control" id="name" name="name" value="{{ old('name', $member->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="role">Role</label>
                <input class="form-control" id="role" name="role" value="{{ old('role', $member->role) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="sort_order">Sort order</label>
                <input class="form-control" type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $member->sort_order) }}" min="0">
            </div>

            <div class="form-group">
                <label class="form-label" for="bio">Bio</label>
                <textarea class="form-control" id="bio" name="bio" rows="8">{{ old('bio', $member->bio) }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
