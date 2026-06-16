@extends('admin.layout')

@section('title', $user->exists ? 'Edit admin user' : 'Add admin user')
@section('page_title', $user->exists ? 'Edit admin user' : 'Add admin user')
@section('page_subtitle', 'Control who can sign in and manage website content.')

@section('content')
    <div class="admin-page-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-light">Back to users</a>
    </div>

    <div class="admin-card">
        <form method="post" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
            @csrf
            @if ($user->exists)
                @method('PUT')
            @endif

            <div class="form-group">
                <label class="form-label" for="name">Name</label>
                <input class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input
                    class="form-control"
                    type="password"
                    id="password"
                    name="password"
                    @if (! $user->exists) required @endif
                    autocomplete="new-password"
                >
                @if ($user->exists)
                    <p class="form-helper">Leave blank to keep the current password.</p>
                @endif
                <p class="form-helper">Use at least 12 characters with uppercase, lowercase, and a number.</p>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-light" href="{{ route('admin.users.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
