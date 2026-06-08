@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>
    <div class="admin-card">
        <ul>
            <li><strong>{{ $pageCount }}</strong> pages</li>
            <li><strong>{{ $newsCount }}</strong> news posts</li>
            <li><strong>{{ $careerCount }}</strong> job postings</li>
            <li><strong>{{ $boardCount }}</strong> board members</li>
            <li><strong>{{ $portfolioCount }}</strong> portfolio items</li>
            <li><strong>{{ $userCount }}</strong> admin users</li>
        </ul>
    </div>
@endsection
