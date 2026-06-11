@extends('admin.layout')

@section('title', 'Edit: '.$page->title)

@section('content')
    <h1>Edit Page</h1>

    @if ($page->isBuiltIn())
        <p class="admin-help">
            This is a built-in page. You can safely update the content, but the page layout is protected.
        </p>
    @else
        <p class="admin-help">
            This is a custom page. You can edit the page content, publish status, and navigation settings.
        </p>
    @endif

    @include('admin.pages.partials.form', [
        'page' => $page,
        'publishedPages' => $publishedPages,
        'parentPages' => $parentPages,
        'action' => route('admin.pages.update', $page),
        'method' => 'PUT',
    ])
@endsection
