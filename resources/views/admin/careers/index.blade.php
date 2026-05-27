@extends('admin.layout')

@section('title', 'Careers')

@section('content')
    <h1>Careers</h1>
    <p><a class="btn btn-primary" href="{{ route('admin.careers.create') }}">Add posting</a></p>
    <div class="admin-card">
        <table class="admin-table">
            <thead><tr><th>Title</th><th>Closes</th><th>Active</th><th></th></tr></thead>
            <tbody>
                @foreach ($careers as $career)
                    <tr>
                        <td>{{ $career->title }}</td>
                        <td>{{ $career->closes_at?->format('Y-m-d') }}</td>
                        <td>{{ $career->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('admin.careers.edit', $career) }}">Edit</a>
                            <form action="{{ route('admin.careers.destroy', $career) }}" method="post" style="display:inline" onsubmit="return confirm('Delete?')">
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
