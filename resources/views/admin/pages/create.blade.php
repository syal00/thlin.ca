@extends('admin.layout')

@section('title', 'Add Custom Page')
@section('page_title', 'Add Custom Page')
@section('page_subtitle', 'Create a new page for the website. Save as draft or publish when ready.')

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
            'action' => route('admin.pages.store'),
            'method' => 'POST',
        ])
    </div>
@endsection
