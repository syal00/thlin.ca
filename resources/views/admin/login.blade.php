@extends('admin.layout')

@section('title', 'Login')

@section('content')
    <div class="admin-card" style="max-width: 420px; margin: 3rem auto;">
        <h1 style="margin-top: 0;">Admin login</h1>
        <p style="color: var(--thlin-muted);">Sign in to edit site content. No public registration.</p>

        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <p style="margin: 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="post" action="{{ route('admin.login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember" value="1"> Remember me
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Sign in</button>
        </form>
    </div>
@endsection
