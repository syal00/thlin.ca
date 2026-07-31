@php
    /** @var \App\Models\BoardMember $member */
@endphp
<article class="t-card t-card--board">
    <div class="t-card-media">
        @if ($member->photoUrl())
            <img
                src="{{ $member->photoUrl() }}"
                alt="{{ $member->name }}"
                @auth
                    data-editable-image="true"
                    data-model="board"
                    data-id="{{ $member->id }}"
                    data-field="photo"
                @endauth
            >
        @else
            <div
                class="t-card-placeholder"
                @auth
                    data-editable-image="true"
                    data-model="board"
                    data-id="{{ $member->id }}"
                    data-field="photo"
                @endauth
            >Photo</div>
        @endif
    </div>
    <div class="t-card-body">
        <h3>
            <span @include('partials.inline-edit-attrs', ['model' => 'board', 'id' => $member->id, 'field' => 'name', 'type' => 'text'])>{{ $member->name }}</span>
            &mdash;
            <span @include('partials.inline-edit-attrs', ['model' => 'board', 'id' => $member->id, 'field' => 'role', 'type' => 'text'])>{{ $member->role }}</span>
        </h3>
        <p @include('partials.inline-edit-attrs', ['model' => 'board', 'id' => $member->id, 'field' => 'bio', 'type' => 'richtext'])>{!! $member->bio !!}</p>
    </div>
</article>
