@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])
@section('meta_description', $page->meta_description ?? $page->excerpt)

@section('content')
    @include('partials.page-header', ['page' => $page])

    <section class="page-content">
        <div class="container prose">
            {!! $page->body !!}
        </div>
    </section>
@endsection
