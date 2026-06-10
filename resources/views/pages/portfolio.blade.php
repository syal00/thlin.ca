@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])

@section('content')
    @include('partials.page-header', ['page' => $page])

    <section class="section">
        <div
            class="container prose mb-5"
            data-editable="true"
            data-model="page"
            data-id="{{ $page->id }}"
            data-field="body"
        >{!! $page->body !!}</div>
    </section>

    @if ($featured->isNotEmpty())
        <section class="featured-services section light-background">
            <div class="container" data-aos="fade-up">
                <div class="section-title text-center">
                    <h2>Highlighted Projects</h2>
                </div>
                <div class="row gy-4">
                    @foreach ($featured as $item)
                        <div class="col-lg-4 col-md-6">
                            <article class="service-card h-100">
                                <div class="card-media">
                                    @if ($item->image)
                                        <img
                                            src="{{ asset('storage/'.$item->image) }}"
                                            alt="{{ $item->title }}"
                                            class="img-fluid"
                                            data-editable-image="true"
                                            data-model="portfolio"
                                            data-id="{{ $item->id }}"
                                            data-field="image"
                                        >
                                    @else
                                        <div
                                            class="portfolio-image-placeholder"
                                            data-editable-image="true"
                                            data-model="portfolio"
                                            data-id="{{ $item->id }}"
                                            data-field="image"
                                        >Click to add image</div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="card-head">
                                        <h3
                                            data-editable="true"
                                            data-model="portfolio"
                                            data-id="{{ $item->id }}"
                                            data-field="title"
                                        >{{ $item->title }}</h3>
                                    </div>
                                    <p
                                        data-editable="true"
                                        data-model="portfolio"
                                        data-id="{{ $item->id }}"
                                        data-field="excerpt"
                                    >{{ $item->excerpt }}</p>
                                    <div class="card-foot">
                                        <a href="{{ $item->url }}" class="link-action" target="_blank" rel="noopener">View Project <i class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($past->isNotEmpty())
        <section class="section">
            <div class="container" data-aos="fade-up">
                <div class="section-title text-center">
                    <h2>Past Projects</h2>
                </div>
                <div class="row gy-4">
                    @foreach ($past as $item)
                        <div class="col-lg-6">
                            <article class="service-card h-100">
                                <div class="card-media">
                                    @if ($item->image)
                                        <img
                                            src="{{ asset('storage/'.$item->image) }}"
                                            alt="{{ $item->title }}"
                                            class="img-fluid"
                                            data-editable-image="true"
                                            data-model="portfolio"
                                            data-id="{{ $item->id }}"
                                            data-field="image"
                                        >
                                    @else
                                        <div
                                            class="portfolio-image-placeholder"
                                            data-editable-image="true"
                                            data-model="portfolio"
                                            data-id="{{ $item->id }}"
                                            data-field="image"
                                        >Click to add image</div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h3
                                        data-editable="true"
                                        data-model="portfolio"
                                        data-id="{{ $item->id }}"
                                        data-field="title"
                                    >{{ $item->title }}</h3>
                                    <p
                                        data-editable="true"
                                        data-model="portfolio"
                                        data-id="{{ $item->id }}"
                                        data-field="excerpt"
                                    >{{ $item->excerpt }}</p>
                                    <div class="card-foot">
                                        <a href="{{ $item->url }}" class="link-action" target="_blank" rel="noopener">View Project <i class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="call-to-action section dark-background">
        <div class="container text-center" data-aos="fade-up">
            <h2>Interested in Collaborating?</h2>
            <p>We work with partners to improve information systems and connect the people of Ontario to relevant health and community services.</p>
            <a href="mailto:{{ $thlin['contact_email'] }}" class="btn btn-solid">{{ $thlin['contact_email'] }}</a>
        </div>
    </section>
@endsection
