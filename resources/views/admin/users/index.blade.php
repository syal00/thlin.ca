@extends('admin.layout')

@section('title', 'Admin users')

@section('content')
    <h1>Admin users</h1>
    <p>Manage the small set of people who can sign in and edit website content. Public registration remains disabled.</p>

    @if ($users->count() < $maxUsers)
        <p><a class="btn btn-primary" href="{{ route('admin.users.create') }}">Add admin user</a></p>
    @else
        <p><strong>{{ $users->count() }} of {{ $maxUsers }}</strong> admin user accounts are in use.</p>
    @endif

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user) }}">Edit</a>
                            @if (! auth()->user()->is($user))
                                <form action="{{ route('admin.users.destroy', $user) }}" method="post" style="display:inline" onsubmit="return confirm('Delete this admin user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="link-button">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
