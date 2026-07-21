@extends('admin.layout')

@section('title', 'Quick Website Edits')
@section('page_title', 'Quick Website Edits')
@section('page_subtitle', 'Edit headings and short text on the live website. Use Pages in the CMS for full content and settings.')

@section('content')
    <div class="admin-card admin-card--spaced">
        <h2>How inline editing works</h2>
        @include('partials.admin-inline-editing-help')
        <div class="form-actions">
            <a href="{{ route('home', ['edit' => 1]) }}" target="_blank" rel="noopener" class="btn btn-primary" data-admin-open-editor>Open Website Editor</a>
        </div>
    </div>

    @php
        $publishedPages = \App\Models\Page::published()->orderBy('title')->get();
    @endphp

    @include('partials.admin-published-pages-table')
@endsection
