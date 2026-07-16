@php
    /** @var \App\Models\BoardMember $member */
@endphp
<article class="t-card t-card--board">
    <div class="t-card-media">
        @if ($member->photoUrl())
            <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}">
        @else
            <div class="t-card-placeholder">Photo</div>
        @endif
    </div>
    <div class="t-card-body">
        <h3 @include('partials.inline-edit-attrs', ['model' => 'board', 'id' => $member->id, 'field' => 'name', 'type' => 'text'])>{{ $member->name }} &mdash; {{ $member->role }}</h3>
        <p @include('partials.inline-edit-attrs', ['model' => 'board', 'id' => $member->id, 'field' => 'bio', 'type' => 'richtext'])>{!! $member->bio !!}</p>
    </div>
</article>
