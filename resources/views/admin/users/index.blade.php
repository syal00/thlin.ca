@extends('admin.layout')

@section('title', 'Admin Users')
@section('page_title', 'Admin Users')
@section('page_subtitle', 'Create CMS accounts for your team. New admins sign in with the default password and choose their own on first login.')

@section('content')
    <div class="admin-alert admin-alert-success">
        Default password for new admins: <strong>{{ $defaultPassword }}</strong>.
        They must set a new password at first sign-in.
    </div>

    <section class="dashboard-grid admin-users-summary" aria-label="CMS account summary">
        <div class="dashboard-stat-card dashboard-stat-card--blue">
            <span class="dashboard-stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            <div>
                <span class="stat-label">Existing CMS accounts</span>
                <strong>{{ $accountStats['total'] }}</strong>
                <p>{{ $accountStats['total'] === 1 ? '1 account registered' : $accountStats['total'].' accounts registered' }}</p>
            </div>
        </div>

        <div class="dashboard-stat-card dashboard-stat-card--green">
            <span class="dashboard-stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 15v7m-6-4 6-6 6 6"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            <div>
                <span class="stat-label">CMS manager</span>
                <strong>{{ $accountStats['mainAdmins'] }}</strong>
                <p>Primary account that manages admin users</p>
            </div>
        </div>

        <div class="dashboard-stat-card dashboard-stat-card--violet">
            <span class="dashboard-stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </span>
            <div>
                <span class="stat-label">Other admins</span>
                <strong>{{ $accountStats['secondaryAdmins'] }}</strong>
                <p>Additional CMS team accounts</p>
            </div>
        </div>

        <div class="dashboard-stat-card dashboard-stat-card--amber">
            <span class="dashboard-stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </span>
            <div>
                <span class="stat-label">Available slots</span>
                <strong>{{ $accountStats['remainingSlots'] }}</strong>
                <p>{{ $users->count() }} of {{ $maxUsers }} accounts in use</p>
            </div>
        </div>
    </section>

    @if ($accountStats['pendingPasswordChange'] > 0)
        <div class="admin-alert admin-alert-error">
            {{ $accountStats['pendingPasswordChange'] }} account(s) still need to set their own password at first sign-in.
        </div>
    @endif

    @if ($users->count() < $maxUsers)
        <div class="admin-page-actions">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add Admin User</a>
        </div>
    @else
        <div class="admin-alert admin-alert-error">
            All {{ $maxUsers }} admin user slots are in use.
        </div>
    @endif

    <div class="admin-table-card">
        <div class="admin-table-card-head">
            <div>
                <h2>Registered CMS accounts</h2>
                <p class="admin-table-card-subtitle">
                    @if ($users->isEmpty())
                        No CMS accounts exist yet.
                    @else
                        These {{ $users->count() }} account(s) can sign in to the CMS.
                    @endif
                </p>
            </div>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr @class(['is-current-user' => auth()->user()->is($user)])>
                            <td>
                                {{ $user->name }}
                                @if (auth()->user()->is($user))
                                    <span class="status-badge status-published">You</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->is_primary)
                                    <span class="status-badge status-published">CMS manager</span>
                                @else
                                    <span class="status-badge status-draft">Admin</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->must_change_password)
                                    <span class="status-badge status-draft">Must change password</span>
                                @else
                                    <span class="status-badge status-published">Active</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at?->format('M j, Y') ?? '—' }}</td>
                            <td>
                                <div class="admin-row-actions">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">Edit</a>
                                    @if (! $user->is_primary && ! auth()->user()->is($user))
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="post" class="admin-inline-form" onsubmit="return confirm('Delete this admin user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-table-empty">
                                No CMS accounts found. Add the first admin user to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
