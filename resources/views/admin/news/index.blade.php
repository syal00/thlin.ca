@extends('admin.layout')

@section('title', 'News')

@section('content')
    <h1>News</h1>
    <p><a class="btn btn-primary" href="{{ route('admin.news.create') }}">Add post</a></p>
    <div class="admin-card">
        <table class="admin-table">
            <thead><tr><th>Title</th><th>Date</th><th></th></tr></thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->published_at?->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('admin.news.edit', $post) }}">Edit</a>
                            <form action="{{ route('admin.news.destroy', $post) }}" method="post" style="display:inline" onsubmit="return confirm('Delete this post?')">
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
