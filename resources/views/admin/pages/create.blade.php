@extends('admin.layout')

@section('title', 'Add Custom Page')

@section('content')
    <h1>Add Custom Page</h1>
    <p class="admin-help">
        Create a new page using the standard website template.
    </p>

    @include('admin.pages.partials.form', [
        'page' => $page,
        'publishedPages' => $publishedPages,
        'parentPages' => $parentPages,
        'action' => route('admin.pages.store'),
        'method' => 'POST',
    ])
@endsection
