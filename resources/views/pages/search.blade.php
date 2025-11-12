@extends('frontend.layouts.app')

@section('styles')

@endsection

@section('content')

    <div class="results-header">
        <div class="container">
            <div class="row valign-wrapper">
                <div class="col s12 m8">
                    <h5>Search results <small class="grey-text">({{ method_exists($properties,'total') ? $properties->total() : count($properties) }})</small></h5>
                </div>
                <div class="col s12 m4 right-align">
                    @php
                        $baseUrl = url()->current();
                        $qsNewest = request()->fullUrlWithQuery(['sort' => 'newest']);
                        $qsAsc    = request()->fullUrlWithQuery(['sort' => 'price_asc']);
                        $qsDesc   = request()->fullUrlWithQuery(['sort' => 'price_desc']);
                    @endphp
                    <a href="{{ $qsNewest }}" class="chip-filter {{ request('sort') === 'newest' || request('sort') === null ? 'active' : '' }}">Newest</a>
                    <a href="{{ $qsAsc }}" class="chip-filter {{ request('sort') === 'price_asc' ? 'active' : '' }}">Price ↑</a>
                    <a href="{{ $qsDesc }}" class="chip-filter {{ request('sort') === 'price_desc' ? 'active' : '' }}">Price ↓</a>
                </div>
            </div>
        </div>
    </div>

    <section>
        <div class="container">
            <div class="row">

                <div class="col s12 m4 card">

                    <h2 class="sidebar-title">search property</h2>

                    <form class="sidebar-search" action="{{ route('search')}}" method="GET">

                        <div class="searchbar">
                            <div class="input-field col s12">
                                <input type="text" name="city" id="autocomplete-input-sidebar" class="autocomplete custominputbox" autocomplete="off" value="{{ request('city') }}">
                                <label for="autocomplete-input-sidebar" class="{{ request('city') ? 'active' : '' }}">Enter City or State</label>
                            </div>
                            <div class="input-field col s12">
                                <input type="text" name="q" id="keyword" class="custominputbox" value="{{ request('q') }}">
                                <label for="keyword" class="{{ request('q') ? 'active' : '' }}">Keywords (title, address, city)</label>
                            </div>
    
                            <div class="input-field col s12">
                                <select name="type" class="browser-default">
                                    <option value="" disabled {{ request('type') ? '' : 'selected' }}>Choose Type</option>
                                    <option value="apartment" {{ request('type') === 'apartment' ? 'selected' : '' }}>Apartment</option>
                                    <option value="house" {{ request('type') === 'house' ? 'selected' : '' }}>House</option>
                                </select>
                            </div>
    
                            <div class="input-field col s12">
                                <select name="purpose" class="browser-default">
                                    <option value="" disabled {{ request('purpose') ? '' : 'selected' }}>Choose Purpose</option>
                                    <option value="rent" {{ request('purpose') === 'rent' ? 'selected' : '' }}>Rent</option>
                                    <option value="sale" {{ request('purpose') === 'sale' ? 'selected' : '' }}>Sale</option>
                                </select>
                            </div>
                            
                            <div class="input-field col s12">
                                <select name="sort" class="browser-default">
                                    <option value="" disabled {{ request('sort') ? '' : 'selected' }}>Sort by</option>
                                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                </select>
                            </div>
    
                            <div class="input-field col s12">
                                <select name="bedroom" class="browser-default">
                                    <option value="" disabled {{ request('bedroom') ? '' : 'selected' }}>Choose Bedroom</option>
                                    @foreach($bedroomdistinct as $bedroom)
                                        <option value="{{$bedroom->bedroom}}" {{ (string) $bedroom->bedroom === (string) request('bedroom') ? 'selected' : '' }}>{{$bedroom->bedroom}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="input-field col s12">
                                <select name="bathroom" class="browser-default">
                                    <option value="" disabled {{ request('bathroom') ? '' : 'selected' }}>Choose Bathroom</option>
                                    @foreach($bathroomdistinct as $bathroom)
                                        <option value="{{$bathroom->bathroom}}" {{ (string) $bathroom->bathroom === (string) request('bathroom') ? 'selected' : '' }}>{{$bathroom->bathroom}}</option>
                                    @endforeach
                                </select>
                            </div>
    
                            <div class="input-field col s12">
                                <input type="number" name="minprice" id="minprice" class="custominputbox" value="{{ request('minprice') }}">
                                <label for="minprice" class="{{ request('minprice') ? 'active' : '' }}">Min Price</label>
                            </div>
    
                            <div class="input-field col s12">
                                <input type="number" name="maxprice" id="maxprice" class="custominputbox" value="{{ request('maxprice') }}">
                                <label for="maxprice" class="{{ request('maxprice') ? 'active' : '' }}">Max Price</label>
                            </div>
    
                            <div class="input-field col s12">
                                <input type="number" name="minarea" id="minarea" class="custominputbox" value="{{ request('minarea') }}">
                                <label for="minarea" class="{{ request('minarea') ? 'active' : '' }}">Floor Min Area</label>
                            </div>
    
                            <div class="input-field col s12">
                                <input type="number" name="maxarea" id="maxarea" class="custominputbox" value="{{ request('maxarea') }}">
                                <label for="maxarea" class="{{ request('maxarea') ? 'active' : '' }}">Floor Max Area</label>
                            </div>
                            
                            <div class="input-field col s12">
                                <div class="switch">
                                    <label>
                                        <input type="checkbox" name="featured" {{ request()->has('featured') ? 'checked' : '' }}>
                                        <span class="lever"></span>
                                        Featured
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col s12">
                                <button class="btn btnsearch indigo" type="submit">
                                    <i class="material-icons left">search</i>
                                    <span>SEARCH</span>
                                </button>
                            </div>
                        </div>
    
                    </form>

                </div>

                <div class="col s12 m8">

                    @foreach($properties as $property)
                        <div class="card horizontal">
                            <div>
                                <div class="card-content property-content">
                                    @if(Storage::disk('public')->exists('property/'.$property->image) && $property->image)
                                        <div class="card-image blog-content-image">
                                            <img src="{{Storage::url('property/'.$property->image)}}" alt="{{$property->title}}">
                                        </div>
                                    @endif
                                    <span class="card-title search-title" title="{{$property->title}}">
                                        <a href="{{ route('property.show',$property->slug) }}">{{ $property->title }}</a>
                                    </span>
                                    
                                    <div class="address">
                                        <i class="small material-icons left">location_city</i>
                                        <span>{{ ucfirst($property->city) }}</span>
                                    </div>
                                    <div class="address">
                                        <i class="small material-icons left">place</i>
                                        <span>{{ ucfirst($property->address) }}</span>
                                    </div>

                                    <h5>
                                        &dollar;{{ $property->price }}
                                        <small class="right">{{ $property->type }} for {{ $property->purpose }}</small>
                                    </h5>

                                </div>
                                <div class="card-action property-action clearfix">
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
                                        Area: <strong>{{ $property->area}}</strong> Sq Ft
                                    </span>
                                    <span class="btn-flat">
                                        <i class="material-icons">comment</i>
                                        {{ $property->comments_count}}
                                    </span>

                                    @if($property->featured == 1)
                                        <span class="right featured-stars">
                                            <i class="material-icons">stars</i>
                                        </span>
                                    @endif                                    

                                </div>
                            </div>
                        </div>
                    @endforeach


                    <div class="m-t-30 m-b-60 center">
                        {{ $properties->appends(request()->all())->links() }}
                    </div>
        
                </div>

            </div>
        </div>
    </section>

@endsection

@section('scripts')

@endsection