<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Real Estate') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('frontend/css/materialize.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">

    @yield('styles')
    
    <link href="{{ asset('frontend/css/styles.css') }}" rel="stylesheet">
</head>

    @php
        // Default global page background image; replace the file to change site-wide
        $defaultPageBg = asset('frontend/images/page-bg.jpg');
        // Allow per-page override: set $pageBg from controller or view if needed
        $activePageBg = isset($pageBg) && $pageBg ? $pageBg : $defaultPageBg;
    @endphp
    <body class="has-page-bg" style="--bg-page: url('{{ $activePageBg }}')">
        
        {{-- MAIN NAVIGATION BAR --}}
        @include('frontend.partials.navbar')

        {{-- SLIDER SECTION (disabled by default; enable by passing $enableSlider=true from controller) --}}
        @if(Request::is('/') && !empty($enableSlider))
            @include('frontend.partials.slider')
        @endif

        {{-- SEARCH BAR --}}
        @include('frontend.partials.search')
        
        {{-- MAIN CONTENT --}}
        <div class="main">
            @yield('content')
        </div>

        {{-- FOOTER --}}
        @include('frontend.partials.footer')


        <!--JavaScript at end of body for optimized loading-->
        {{-- <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script> --}}
        <script type="text/javascript" src="{{ asset('frontend/js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('frontend/js/materialize.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        @php
            use Brian2694\Toastr\Facades\Toastr;
        @endphp
        <?php echo \Brian2694\Toastr\Facades\Toastr::message(); ?>
        <script>
            var bladeErrors = <?php echo json_encode($errors->all()); ?>;
            if (bladeErrors && bladeErrors.length) {
                bladeErrors.forEach(function(error) {
                    toastr.error(error, 'Error', {
                        closeButton: true,
                        progressBar: true
                    });
                });
            }
        </script>

        @yield('scripts')

        <script>
        $(document).ready(function(){
            $('.sidenav').sidenav();

            $('.carousel.carousel-slider').carousel({
                fullWidth: true,
                indicators: true,
            });

            $('.carousel.testimonials').carousel({
                indicators: true,
            });

            var city_list =<?php echo json_encode($citylist);?>;
            $('input.autocomplete').autocomplete({
                data: city_list
            });

            $(".dropdown-trigger").dropdown({
                top: '65px'
            });

            $('.tooltipped').tooltip();

            // Simple horizontal scroll controls for latest listings
            $('.scroll-btn').on('click', function(){
                var target = $(this).data('target');
                var dir = $(this).hasClass('left') ? -1 : 1;
                var $el = $(target);
                $el.animate({ scrollLeft: $el.scrollLeft() + (dir * 300) }, 250);
            });

        });
        </script>

    </body>
  </html>