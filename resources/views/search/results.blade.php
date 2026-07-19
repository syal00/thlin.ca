@extends('layouts.app')

@section('title', 'Search - '.$thlin['name'])
@section('meta_description', 'Search pages, tools, services, and resources across the THLIN website.')

@section('hero')
    @include('partials.hero-page', [
        'editable' => false,
        'heroTitle' => 'Search THLIN Resources',
        'heroSubtitle' => 'Find pages, tools, services, and resources across the THLIN website.',
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Search', 'current' => true],
        ],
    ])
@endsection

@section('content')
    @include('search.index')

    @include('partials.cta-section')
@endsection
