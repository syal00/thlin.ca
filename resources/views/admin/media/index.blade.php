@extends('admin.layout')

@section('title', 'Uploaded Files')

@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Uploaded Files</h1>
            <p class="admin-help">Upload and manage PDF files such as Annual Reports.</p>
        </div>

        <a href="{{ route('admin.media.create') }}" class="btn btn-primary">Upload PDF</a>
    </div>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>File Title</th>
                    <th>Original File Name</th>
                    <th>Size</th>
                    <th>Uploaded</th>
                    <th>File Link</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mediaFiles as $file)
                    <tr>
                        <td>{{ $file->title }}</td>
                        <td>{{ $file->original_name }}</td>
                        <td>{{ $file->formatted_size }}</td>
                        <td>{{ $file->created_at->format('M d, Y') }}</td>
                        <td>
                            <input type="text" class="admin-copy-input" value="{{ $file->url }}" readonly onclick="this.select(); document.execCommand('copy');">
                            <small>Click to copy</small>
                        </td>
                        <td class="admin-actions">
                            <a href="{{ $file->url }}" target="_blank" rel="noopener" class="btn btn-sm">Open</a>

                            <form method="post" action="{{ route('admin.media.destroy', $file) }}" class="admin-inline-form" onsubmit="return confirm('Delete this file?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No files uploaded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($mediaFiles->hasPages())
        <div class="admin-pagination">
            {{ $mediaFiles->links() }}
        </div>
    @endif
@endsection
