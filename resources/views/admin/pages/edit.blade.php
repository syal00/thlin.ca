@extends('admin.layout')

@section('title', 'Edit: '.$page->title)
@section('page_title', 'Edit Page')
@section('page_subtitle')
    @if ($page->isBuiltIn())
        Built-in page — update text safely without changing the layout.
    @else
        Update this custom page. Save as draft or publish when ready.
    @endif
@endsection

@section('content')
    <div class="cms-form-page">
        <div class="admin-page-actions">
            <a href="{{ route('admin.pages.index') }}" class="btn btn-light">Back to pages</a>
        </div>

        @include('admin.pages.partials.form', [
            'page' => $page,
            'publishedPages' => $publishedPages,
            'parentPages' => $parentPages,
            'parentPageGroups' => $parentPageGroups,
            'action' => route('admin.pages.update', $page),
            'method' => 'PUT',
        ])
    </div>
@endsection
