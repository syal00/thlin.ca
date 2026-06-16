@extends('admin.layout')

@section('title', 'Board')
@section('page_title', 'Board of Directors')
@section('page_subtitle', 'Manage board member profiles and display order.')

@section('content')
    <div class="admin-page-actions">
        <a href="{{ route('admin.board.create') }}" class="btn btn-primary">Add Board Member</a>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-card-head">
            <h2>All board members</h2>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->role }}</td>
                            <td>{{ $member->sort_order }}</td>
                            <td>
                                <div class="admin-row-actions">
                                    <a href="{{ route('admin.board.edit', $member) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('admin.board.destroy', $member) }}" method="post" class="admin-inline-form" onsubmit="return confirm('Delete this member?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table-empty">No board members yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
