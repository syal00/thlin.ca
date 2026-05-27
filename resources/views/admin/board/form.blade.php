@extends('admin.layout')

@section('title', $member->exists ? 'Edit board member' : 'Add board member')

@section('content')
    <h1>{{ $member->exists ? 'Edit' : 'Add' }} board member</h1>
    <div class="admin-card">
        <form method="post" action="{{ $member->exists ? route('admin.board.update', $member) : route('admin.board.store') }}">
            @csrf
            @if ($member->exists) @method('PUT') @endif
            <div class="form-group"><label for="name">Name</label><input id="name" name="name" value="{{ old('name', $member->name) }}" required></div>
            <div class="form-group"><label for="role">Role</label><input id="role" name="role" value="{{ old('role', $member->role) }}" required></div>
            <div class="form-group"><label for="sort_order">Sort order</label><input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $member->sort_order) }}" min="0"></div>
            <div class="form-group"><label for="bio">Bio</label><textarea id="bio" name="bio" rows="8">{{ old('bio', $member->bio) }}</textarea></div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
