@extends('admin.layout')

@section('title', $user->exists ? 'Edit admin user' : 'Add admin user')
@section('page_title', $user->exists ? 'Edit admin user' : 'Add admin user')
@section('page_subtitle', $user->exists
    ? 'Update account details or reset the sign-in password.'
    : 'New admins receive the default password and must choose their own at first sign-in.')

@section('content')
    <div class="admin-page-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-light">Back to admin users</a>
    </div>

    <div class="admin-card">
        @if (! $user->exists)
            <div class="admin-alert admin-alert-success">
                A default password of <strong>{{ $defaultPassword }}</strong> will be assigned automatically.
                The new admin must change it when they first sign in.
            </div>
        @endif

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

            @if ($user->exists && ! $user->is_primary)
                <div class="form-group">
                    <label class="form-label" for="password">New password</label>
                    <input
                        class="form-control"
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                    >
                    <p class="form-helper">Optional. Use at least 12 characters with uppercase, lowercase, and a number.</p>
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="reset_to_default_password" value="1" @checked(old('reset_to_default_password'))>
                        <span>Reset to default password ({{ $defaultPassword }}) and require change on next sign-in</span>
                    </label>
                </div>
            @elseif ($user->exists && $user->is_primary)
                <p class="form-helper">The CMS manager password is changed from the sign-in screen or Change password in the sidebar.</p>
            @endif

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-light" href="{{ route('admin.users.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
