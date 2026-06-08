@extends('admin.layout')

@section('title', 'Inline Editing')

@section('content')
    <h1>Inline Editing</h1>
    <div class="admin-card">
        <h2>Live Website Editing</h2>
        <p>Inline editing allows an administrator to edit website content directly from the live website. After enabling edit mode, the admin can click on editable text or images and update them without using a separate backend form.</p>
        <ul>
            <li>Admin logs in to the dashboard.</li>
            <li>Admin opens the public website.</li>
            <li>Admin enables Edit Mode.</li>
            <li>Admin clicks website text or images to update them.</li>
            <li>Changes are saved into the CMS database.</li>
        </ul>
        <p><a class="btn btn-primary" href="{{ route('home') }}">Open Website Edit Mode</a></p>
    </div>
@endsection
