@extends('admin.layout')

@section('title', $user->exists ? 'Edit admin user' : 'Add admin user')

@section('content')
    <h1>{{ $user->exists ? 'Edit' : 'Add' }} admin user</h1>

    <div class="admin-card">
        <form method="post" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
            @csrf
            @if ($user->exists)
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Name</label>
                <input id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    @if (! $user->exists) required @endif
                    autocomplete="new-password"
                >
                @if ($user->exists)
                    <p class="form-help">Leave blank to keep the current password.</p>
                @endif
                <p class="form-help">Use at least 12 characters with uppercase, lowercase, and a number.</p>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a class="btn" href="{{ route('admin.users.index') }}">Cancel</a>
        </form>
    </div>
@endsection
