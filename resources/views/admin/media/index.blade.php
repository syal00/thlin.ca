@extends('admin.layout')

@section('title', 'Uploaded Files')
@section('page_title', 'Uploaded Files')
@section('page_subtitle', 'Upload and manage PDF files such as Annual Reports.')

@section('content')
    @if ($isServerlessDeploy && ! $persistentStorageConfigured)
        <div class="admin-alert admin-alert-warning">
            <strong>Cloud storage is not configured on this server.</strong>
            PDF preview links may stop working after a redeploy. Add <code>CLOUDINARY_URL</code> to the Vercel environment variables for durable file hosting, then re-upload your PDFs.
        </div>
    @endif

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
                        <th>File name</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Uploaded</th>
                        <th>Public URL</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mediaFiles as $mediaFile)
                        <tr>
                            <td>
                                <strong>{{ $mediaFile->title }}</strong><br>
                                <small class="form-helper">{{ $mediaFile->original_name }}</small>
                            </td>
                            <td>
                                <span class="media-type-badge">
                                    {{ strtoupper(pathinfo($mediaFile->file_name ?? $mediaFile->file_path ?? '', PATHINFO_EXTENSION)) ?: strtoupper($mediaFile->file_type ?: 'FILE') }}
                                </span>
                            </td>
                            <td>{{ $mediaFile->formatted_size }}</td>
                            <td>{{ $mediaFile->created_at?->format('M j, Y') }}</td>
                            <td>
                                <code>{{ $mediaFile->url }}</code><br>
                                <button type="button" class="admin-btn admin-btn-secondary copy-link-btn" data-copy="{{ $mediaFile->url }}">Copy Link</button>
                            </td>
                            <td>
                                <div class="admin-row-actions">
                                    @if (strtolower(pathinfo($mediaFile->file_name ?? $mediaFile->file_path ?? '', PATHINFO_EXTENSION)) === 'pdf')
                                        <a href="{{ $mediaFile->url }}" target="_blank" rel="noopener" class="admin-btn admin-btn-secondary">Preview PDF</a>
                                        <a href="{{ $mediaFile->url }}" download class="admin-btn admin-btn-secondary">Download</a>
                                    @else
                                        <a href="{{ $mediaFile->url }}" target="_blank" rel="noopener" class="admin-btn admin-btn-secondary">Open</a>
                                        <a href="{{ $mediaFile->url }}" download class="admin-btn admin-btn-secondary">Download</a>
                                    @endif
                                    <form method="post" action="{{ route('admin.media.destroy', $mediaFile) }}" class="admin-inline-form" onsubmit="return confirm('Delete this file?')">
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
