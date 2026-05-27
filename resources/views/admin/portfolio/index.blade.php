@extends('admin.layout')

@section('title', 'Portfolio')

@section('content')
    <h1>Portfolio</h1>
    <p><a class="btn btn-primary" href="{{ route('admin.portfolio.create') }}">Add item</a></p>
    <div class="admin-card">
        <table class="admin-table">
            <thead><tr><th>Title</th><th>Featured</th><th></th></tr></thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->featured ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('admin.portfolio.edit', $item) }}">Edit</a>
                            <form action="{{ route('admin.portfolio.destroy', $item) }}" method="post" style="display:inline" onsubmit="return confirm('Delete?')">
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
