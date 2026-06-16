@extends('admin.layout')

@section('title', 'Portfolio')
@section('page_title', 'Portfolio')
@section('page_subtitle', 'Manage featured and past portfolio projects.')

@section('content')
    <div class="admin-page-actions">
        <a href="{{ route('admin.portfolio.create') }}" class="btn btn-primary">Add Portfolio Item</a>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-card-head">
            <h2>All portfolio items</h2>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>
                                @if ($item->featured)
                                    <span class="admin-badge admin-badge-success">Featured</span>
                                @else
                                    <span class="admin-badge admin-badge-muted">Standard</span>
                                @endif
                            </td>
                            <td>
                                <div class="admin-row-actions">
                                    <a href="{{ route('admin.portfolio.edit', $item) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('admin.portfolio.destroy', $item) }}" method="post" class="admin-inline-form" onsubmit="return confirm('Delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="admin-table-empty">No portfolio items yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
