@extends('frontend.layouts.app')

@section('content')

    {{-- Explore suburbs section removed as per user request --}}

    <!-- SERVICE SECTION -->


    <section class="section grey lighten-4 center">
        <div class="container">
            <div class="row">
                <h4 class="section-heading">Services</h4>
            </div>
            <div class="row">
                @foreach($services as $service)
                    <div class="col s12 m4">
                        <div class="card-panel">
                            <i class="material-icons large dark-grey-text">{{ $service->icon }}</i>
                            <h5>{{ $service->title }}</h5>
                            <p>{{ $service->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div><!-- Visit eduinreality for more projects -->
    </section>


    <!-- FEATURED SECTION -->

    <section class="section">
        <div class="container">
            <div class="row">
                <h4 class="section-heading">Featured Properties</h4>
            </div>
            <div class="row">

                @foreach($properties as $property)
                    <div class="col s12 m6 l4">
                        <div class="card card-property z-depth-1 hoverable">
                            <div class="card-image property-media">
                                @if(Storage::disk('public')->exists('property/'.$property->image) && $property->image)
                                    <span class="card-image-bg" style="background-image: url('{{ Storage::url("property/".$property->image) }}');"></span>
                                @else
                                    <span class="card-image-bg"></span>
                                @endif
                                <div class="property-badges">
                                    <span class="badge purpose {{ $property->purpose }}">{{ ucfirst($property->purpose) }}</span>
                                    <span class="badge type">{{ ucfirst($property->type) }}</span>
                                    @if($property->featured == 1)
                                        <span class="badge featured" title="Featured"><i class="material-icons tiny">star</i> Featured</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-content property-content">
                                <a href="{{ route('property.show',$property->slug) }}">
                                    <span class="card-title tooltipped" data-position="bottom" data-tooltip="{{ $property->title }}">{{ \Illuminate\Support\Str::limit( $property->title, 18 ) }}</span>
                                </a>

                                <div class="address">
                                    <i class="small material-icons left">location_city</i>
                                    <span>{{ ucfirst($property->city) }}</span>
                                </div>
                                <div class="address">
                                    <i class="small material-icons left">place</i>
                                    <span>{{ ucfirst($property->address) }}</span>
                                </div>
                                <div class="address">
                                    <i class="small material-icons left">check_box</i>
                                    <span>{{ ucfirst($property->type) }} for {{ $property->purpose }}</span>
                                </div>

                                <div class="price-row">
                                    <h5 class="price">
                                        &dollar;{{ number_format($property->price) }}@if($property->purpose === 'rent')<small>/mo</small>@endif
                                    </h5>
                                    <div class="right" id="propertyrating-{{$property->id}}"></div>
                                </div>

                            </div>
                            <div class="card-action property-action">
                                <span class="btn-flat">
                                    <i class="material-icons">check_box</i>
                                    Bedroom: <strong>{{ $property->bedroom}}</strong> 
                                </span>
                                <span class="btn-flat">
                                    <i class="material-icons">check_box</i>
                                    Bathroom: <strong>{{ $property->bathroom}}</strong> 
                                </span>
                                <span class="btn-flat">
                                    <i class="material-icons">check_box</i>
                                    Area: <strong>{{ $property->area}}</strong> Square Feet
                                </span>
                                <span class="btn-flat">
                                    <i class="material-icons">comment</i> 
                                    <strong>{{ $property->comments_count}}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div><!-- Visit eduinreality for more projects -->
    </section>

    <!-- LATEST LISTINGS CAROUSEL -->
    <section class="section latest-listings">
        <div class="container">
            <div class="row">
                <h4 class="section-heading">Latest listings</h4>
            </div>
            <div class="carousel-wrapper">
                <div class="properties-scroll" id="latest-scroll">
                    @foreach($latest as $lp)
                        @php
                            $lpImg = (isset($lp->image) && $lp->image && Storage::disk('public')->exists('property/'.$lp->image))
                                ? Storage::url('property/'.$lp->image)
                                : asset('frontend/images/placeholder.png');
                        @endphp
                        <div class="scroll-card">
                            <a href="{{ route('property.show', $lp->slug) }}" class="scroll-card-inner">
                                <div class="media" style="background-image: url('{{ $lpImg }}');"></div>
                                <div class="info">
                                    <p class="title" title="{{ $lp->title }}">{{ \Illuminate\Support\Str::limit($lp->title, 34) }}</p>
                                    <p class="meta">$ {{ number_format($lp->price) }} @if($lp->purpose==='rent')<small>/mo</small>@endif</p>
                                    <p class="sub">{{ ucfirst($lp->city) }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <button class="scroll-btn left" data-target="#latest-scroll"><i class="material-icons">chevron_left</i></button>
                <button class="scroll-btn right" data-target="#latest-scroll"><i class="material-icons">chevron_right</i></button>
            </div>
        </div>
    </section>


    <!-- TESTIMONIALS SECTION -->

    <section class="section grey lighten-3 center">
        <div class="container">

            <h4 class="section-heading">Testimonials</h4>

            <div class="carousel testimonials">

                @foreach($testimonials as $testimonial)
                    <div class="carousel-item testimonial-item" href="#{{$testimonial->id}}!">
                        <div class="card testimonial-card">
                            <span style="height:20px;display:block;"></span>
                            <div class="card-image testimonial-image">
                                <img src="{{Storage::url('testimonial/'.$testimonial->image)}}">
                            </div>
                            <div class="card-content">
                                <span class="card-title">{{$testimonial->name}}</span>
                                <p class="black-text">
                                    <i>"{{$testimonial->testimonial}}"</i>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>

    </section>


    <!-- BLOG SECTION -->

    <section class="section center">
        <div class="row">
            <h4 class="section-heading">Recent Blog</h4>
        </div>
        <div class="container">
            <div class="row">

                @foreach($posts as $post)
                <div class="col s12 m4">
                    <div class="card">
                        <div class="card-image">
                            @if($post->image && Storage::disk('public')->exists('posts/'.$post->image))
    <span
        class="card-image-bg"
        style="background-image: url('{{ Storage::url("posts/".$post->image) }}');">
    </span>
@endif
                        </div>
                        <div class="card-content">
                            <span class="card-title tooltipped" data-position="bottom" data-tooltip="{{ $post->title }}">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ \Illuminate\Support\Str::limit($post->title, 18) }}</a>
                            </span>
                            {!! \Illuminate\Support\Str::limit($post->body, 120) !!}
                        </div>
                        <div class="card-action blog-action">
                            @if($post->user)
                                <a href="{{ route('blog.author', $post->user->username) }}" class="btn-flat">
                                    <i class="material-icons">person</i>
                                    <span>{{ $post->user->name }}</span>
                                </a>
                            @endif

                            @foreach($post->categories as $category)
                                <a href="{{ route('blog.categories', $category->slug) }}" class="btn-flat">
                                    <i class="material-icons">folder</i>
                                    <span>{{ $category->name }}</span>
                                </a>
                            @endforeach

                            @foreach($post->tags as $tag)
                                <a href="{{ route('blog.tags', $tag->slug) }}" class="btn-flat">
                                    <i class="material-icons">label</i>
                                    <span>{{ $tag->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>
    <!-- Visit eduinreality for more projects -->

@endsection

@section('scripts')
<script type="application/json" id="properties-data">{!! json_encode($properties, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}</script>
<script>
    $(function(){
        const js_properties = JSON.parse(document.getElementById('properties-data').textContent);

        js_properties.forEach(function(element) {
            // Ensure element has an id for selector
            const selector = "#propertyrating-" + (element.id || '');
            if (element && Array.isArray(element.rating) && element.rating.length > 0) {
                const elmt = element.rating;
                let sum = 0;
                for (let i = 0; i < elmt.length; i++) {
                    sum += parseFloat(elmt[i].rating) || 0;
                }
                const avg = sum / elmt.length;
                if (!isNaN(avg)) {
                    $(selector).rateYo({
                        rating: avg,
                        starWidth: "20px",
                        readOnly: true
                    });
                    return;
                }
            }
            // fallback when no ratings or invalid average
            $(selector).rateYo({
                rating: 0,
                starWidth: "20px",
                readOnly: true
            });
        });
    });
</script>
@endsection