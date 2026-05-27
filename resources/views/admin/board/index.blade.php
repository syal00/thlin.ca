@extends('admin.layout')

@section('title', 'Board')

@section('content')
    <h1>Board of Directors</h1>
    <p><a class="btn btn-primary" href="{{ route('admin.board.create') }}">Add member</a></p>
    <div class="admin-card">
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Role</th><th>Order</th><th></th></tr></thead>
            <tbody>
                @foreach ($members as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->role }}</td>
                        <td>{{ $member->sort_order }}</td>
                        <td>
                            <a href="{{ route('admin.board.edit', $member) }}">Edit</a>
                            <form action="{{ route('admin.board.destroy', $member) }}" method="post" style="display:inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="link-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
