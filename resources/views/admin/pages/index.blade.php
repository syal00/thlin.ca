@extends('admin.layout')

@section('title', 'Pages')

@section('content')
    <h1>Pages</h1>
    <p>Select a page to edit title, excerpt, and body content.</p>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Section</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $page)
                    <tr>
                        <td>{{ $page->title }}</td>
                        <td>{{ $page->section }}</td>
                        <td>{{ $page->is_published ? 'Published' : 'Draft' }}</td>
                        <td><a href="{{ route('admin.pages.edit', $page) }}">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
