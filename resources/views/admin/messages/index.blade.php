@extends('admin.layout')

@section('title', 'Messages')
@section('page_title', 'Messages')
@section('page_subtitle', 'View and manage contact form submissions from the website.')

@section('content')
    <div class="admin-page-actions">
        <span class="admin-help-text">Incoming contact messages are saved here for follow-up.</span>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-card-head">
            <h2>Contact messages</h2>
            <p class="admin-table-card-subtitle">Mark items as read after reviewing them or delete messages that are no longer needed.</p>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Organization</th>
                        <th>Status</th>
                        <th>Received</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr>
                            <td>
                                <a href="{{ route('admin.messages.show', $message) }}" class="admin-page-title-link">{{ $message->name }}</a>
                            </td>
                            <td>{{ $message->email }}</td>
                            <td>{{ $message->organization ?: '—' }}</td>
                            <td>
                                @if ($message->status === 'read')
                                    <span class="admin-badge admin-badge-green">Read</span>
                                @else
                                    <span class="admin-badge admin-badge-warning">New</span>
                                @endif
                            </td>
                            <td>{{ $message->created_at?->format('M j, Y') }}</td>
                            <td>
                                <div class="admin-row-actions">
                                    <a href="{{ route('admin.messages.show', $message) }}" class="admin-btn admin-btn-secondary">View</a>
                                    @if ($message->status !== 'read')
                                        <form method="post" action="{{ route('admin.messages.read', $message) }}" class="admin-inline-form">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-primary btn-sm">Mark as read</button>
                                        </form>
                                    @endif
                                    <form method="post" action="{{ route('admin.messages.destroy', $message) }}" class="admin-inline-form" onsubmit="return confirm('Delete this message?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-table-empty">No contact messages yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($messages->hasPages())
        <div class="admin-pagination">
            {{ $messages->links() }}
        </div>
    @endif
@endsection
