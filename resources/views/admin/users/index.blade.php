@extends('admin.layout')

@section('title', 'Admin Users')
@section('page_title', 'Admin Users')
@section('page_subtitle', 'Manage who can sign in and edit website content.')

@section('content')
    @if ($users->count() < $maxUsers)
        <div class="admin-page-actions">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add Admin User</a>
        </div>
    @else
        <div class="admin-alert admin-alert-error">
            {{ $users->count() }} of {{ $maxUsers }} admin user accounts are in use.
        </div>
    @endif

    <div class="admin-table-card">
        <div class="admin-table-card-head">
            <h2>All admin users</h2>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div class="admin-row-actions">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">Edit</a>
                                    @if (! auth()->user()->is($user))
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="post" class="admin-inline-form" onsubmit="return confirm('Delete this admin user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="admin-table-empty">No admin users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
