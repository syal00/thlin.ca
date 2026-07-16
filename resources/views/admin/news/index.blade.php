@extends('admin.layout')

@section('title', 'News')
@section('page_title', 'News')
@section('page_subtitle', 'Manage news posts shown on the public website.')

@section('content')
    <div class="admin-page-actions">
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">Add News Post</a>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-card-head">
            <h2>All news posts</h2>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->published_at?->format('M d, Y') }}</td>
                            <td>
                                <div class="admin-row-actions">
                                    <a href="{{ route('admin.news.edit', $post) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('admin.news.destroy', $post) }}" method="post" class="admin-inline-form" onsubmit="return confirm('Delete this post?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="admin-table-empty">No news posts yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($posts->hasPages())
        <div class="admin-pagination">
            {{ $posts->links() }}
        </div>
    @endif
@endsection
