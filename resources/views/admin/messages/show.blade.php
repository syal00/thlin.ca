@extends('admin.layout')

@section('title', 'Message from '.$message->name)
@section('page_title', 'Contact Message')
@section('page_subtitle', 'Read and respond to a submitted contact form message.')

@section('content')
    <div class="admin-page-actions admin-page-actions--split">
        <a href="{{ route('admin.messages.index') }}" class="btn btn-light">Back to messages</a>
        <div class="admin-row-actions">
            @if ($message->status !== 'read')
                <form method="post" action="{{ route('admin.messages.read', $message) }}" class="admin-inline-form">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary">Mark as read</button>
                </form>
            @endif
            <form method="post" action="{{ route('admin.messages.destroy', $message) }}" class="admin-inline-form" onsubmit="return confirm('Delete this message?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-status-group" style="margin-bottom: 1rem;">
            @if ($message->status === 'read')
                <span class="admin-badge admin-badge-green">Read</span>
            @else
                <span class="admin-badge admin-badge-warning">New</span>
            @endif
        </div>

        <div class="form-grid">
            <div>
                <span class="form-label">Name</span>
                <p class="admin-help" style="margin: 0; color: var(--admin-text);">{{ $message->name }}</p>
            </div>

            <div>
                <span class="form-label">Email</span>
                <p class="admin-help" style="margin: 0; color: var(--admin-text);">{{ $message->email }}</p>
            </div>

            <div>
                <span class="form-label">Organization</span>
                <p class="admin-help" style="margin: 0; color: var(--admin-text);">{{ $message->organization ?: '—' }}</p>
            </div>

            <div>
                <span class="form-label">Received</span>
                <p class="admin-help" style="margin: 0; color: var(--admin-text);">{{ $message->created_at?->format('F j, Y \a\t g:i a') }}</p>
            </div>

            <div>
                <span class="form-label">Message</span>
                <div class="admin-card" style="box-shadow: none; background: #f8fafc; padding: 18px;">
                    {{ $message->message }}
                </div>
            </div>
        </div>
    </div>
@endsection
