@extends('admin.layout')

@section('title', 'Careers')
@section('page_title', 'Careers')
@section('page_subtitle', 'Manage job postings on the careers page.')

@section('content')
    <div class="admin-page-actions">
        <a href="{{ route('admin.careers.create') }}" class="btn btn-primary">Add Job Posting</a>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-card-head">
            <h2>All job postings</h2>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Closes</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($careers as $career)
                        <tr>
                            <td>{{ $career->title }}</td>
                            <td>{{ $career->closes_at?->format('M d, Y') }}</td>
                            <td>
                                @if ($career->is_active)
                                    <span class="admin-badge admin-badge-success">Active</span>
                                @else
                                    <span class="admin-badge admin-badge-muted">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="admin-row-actions">
                                    <a href="{{ route('admin.careers.edit', $career) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('admin.careers.destroy', $career) }}" method="post" class="admin-inline-form" onsubmit="return confirm('Delete this posting?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table-empty">No job postings yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
