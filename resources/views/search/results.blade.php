@extends('layouts.app')

@section('title', 'Search - '.$thlin['name'])
@section('meta_description', 'Search pages, tools, services, and resources across the THLIN website.')

@section('content')
    @include('search.index')

    @include('partials.cta-section')
@endsection
