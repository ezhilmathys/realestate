
<!-- SEARCH SECTION (REA-inspired) -->

@php
    $isHome = request()->is('/');
    $heroImage = asset('frontend/images/real_estate.jpg');
    $useOverlay = $isHome && !(!empty($enableSlider) === false ? false : (empty($enableSlider))); // true only if slider is enabled
    // simplify: overlay only when slider is enabled
    $useOverlay = $isHome && !empty($enableSlider);
    // classes for section background depending on page
    $sectionClasses = $isHome
        ? 'white-text center ' . ($useOverlay ? 'hero-overlay ' : '') . 'bg-cover'
        : 'grey lighten-4 black-text center';
@endphp
<section class="search-hero {{ $sectionClasses }}" @if($isHome) style="background-image:linear-gradient(rgba(0,0,0,0.45),rgba(0,0,0,0.45)),url('{{ $heroImage }}')" @endif>
    <div class="container">
        <div class="row m-b-0">
            <div class="col s12">
                @if($isHome)
                    <h3 class="hero-title">Find your place to live</h3>
                    <p class="hero-sub">Search properties for sale and rent across top suburbs</p>
                @endif
                <form action="{{ route('search')}}" method="GET" class="rea-search-form">
                    <div class="tabs row m-0">
                        <input type="hidden" name="purpose" id="purpose-input" value="{{ request('purpose', 'sale') }}">
                        <a href="#" class="tab-link col s4 {{ request('purpose','sale') === 'sale' ? 'active' : '' }}" data-purpose="sale">
                            <i class="material-icons left">home</i>
                            Buy
                        </a>
                        <a href="#" class="tab-link col s4 {{ request('purpose') === 'rent' ? 'active' : '' }}" data-purpose="rent">
                            <i class="material-icons left">meeting_room</i>
                            Rent
                        </a>
                        <a href="#" class="tab-link col s4 {{ request('purpose') === 'sold' ? 'active' : '' }} disabled" data-purpose="sold" title="Sold search coming soon">
                            <i class="material-icons left">check_circle</i>
                            Sold
                        </a>
                    </div>

                    <div class="searchbar z-depth-2 {{ $isHome ? 'white-text' : '' }}">
                        <div class="input-field col s12 m4">
                            <input type="text" name="city" id="autocomplete-input" class="autocomplete custominputbox" autocomplete="off" value="{{ request('city') }}">
                            <label for="autocomplete-input" class="{{ request('city') ? 'active' : '' }}">Search by suburb, city or state</label>
                        </div>

                        <div class="input-field col s6 m2">
                            <select name="type" class="browser-default">
                                <option value="" {{ request('type') ? '' : 'selected' }}>Property type</option>
                                <option value="apartment" {{ request('type')==='apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="house" {{ request('type')==='house' ? 'selected' : '' }}>House</option>
                            </select>
                        </div>

                        <div class="input-field col s6 m2">
                            <select name="bedroom" class="browser-default">
                                <option value="" {{ request('bedroom') ? '' : 'selected' }}>Beds</option>
                                @if(isset($bedroomdistinct))
                                    @foreach($bedroomdistinct as $bedroom)
                                        <option value="{{$bedroom->bedroom}}" {{ (string)$bedroom->bedroom === (string)request('bedroom') ? 'selected' : '' }}>{{$bedroom->bedroom}}+</option>
                                    @endforeach
                                @else
                                    <option value="1">1+</option>
                                    <option value="2">2+</option>
                                    <option value="3">3+</option>
                                    <option value="4">4+</option>
                                @endif
                            </select>
                        </div>

                        <div class="input-field col s6 m2">
                            <input type="number" name="minprice" id="minprice" class="custominputbox" value="{{ request('minprice') }}">
                            <label for="minprice" class="{{ request('minprice') ? 'active' : '' }}">Min price</label>
                        </div>

                        <div class="input-field col s6 m2">
                            <input type="number" name="maxprice" id="maxprice" class="custominputbox" value="{{ request('maxprice') }}">
                            <label for="maxprice" class="{{ request('maxprice') ? 'active' : '' }}">Max price</label>
                        </div>

                        <div class="input-field col s12 m12 l2 right-align">
                            <button class="btn btnsearch waves-effect waves-light w100" type="submit">
                                <i class="material-icons left">search</i>
                                Search
                            </button>
                        </div>
                    </div>

                    <div class="row m-t-10 m-b-0">
                        <div class="col s12 left-align">
                            <a href="{{ route('search') }}" class="{{ $isHome ? 'white-text' : 'blue-text text-darken-2' }} underline">More filters</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    (function(){
        // Tabs to set purpose value
        const purposeInput = document.getElementById('purpose-input');
        document.querySelectorAll('.rea-search-form .tab-link').forEach(function(tab){
            tab.addEventListener('click', function(e){
                e.preventDefault();
                if (this.classList.contains('disabled')) return;
                document.querySelectorAll('.rea-search-form .tab-link').forEach(t=>t.classList.remove('active'));
                this.classList.add('active');
                const purpose = this.getAttribute('data-purpose');
                // Map Buy->sale, Rent->rent, Sold->sold (not implemented in backend yet)
                purposeInput.value = purpose === 'sale' ? 'sale' : (purpose === 'rent' ? 'rent' : '');
            });
        });
    })();
</script>