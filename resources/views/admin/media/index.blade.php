@extends('admin.layout')

@section('title', 'Uploaded Files')
@section('page_title', 'Uploaded Files')
@section('page_subtitle', 'Upload and manage PDF files such as Annual Reports.')

@section('content')
    <div class="admin-page-actions">
        <a href="{{ route('admin.media.create') }}" class="btn btn-primary">Upload PDF</a>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-card-head">
            <h2>All uploaded files</h2>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>File title</th>
                        <th>Original name</th>
                        <th>Size</th>
                        <th>Uploaded</th>
                        <th>File link</th>
                        <th>Actions</th>
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
                                <small class="form-helper">Click to copy</small>
                            </td>
                            <td>
                                <div class="admin-row-actions">
                                    <a href="{{ $file->url }}" target="_blank" rel="noopener" class="btn btn-light btn-sm">Open</a>
                                    <form method="post" action="{{ route('admin.media.destroy', $file) }}" class="admin-inline-form" onsubmit="return confirm('Delete this file?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-table-empty">No files uploaded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($mediaFiles->hasPages())
        <div class="admin-pagination">
            {{ $mediaFiles->links() }}
        </div>
    @endif
@endsection
