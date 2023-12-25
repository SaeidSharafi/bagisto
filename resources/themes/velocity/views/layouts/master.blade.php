<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-KFQ1CRSG4Y"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-KFQ1CRSG4Y');
    </script>

    {{-- title --}}
    <title>@yield('page_title')</title>

    {{-- meta data --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url()->to('/') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {!! view_render_event('bagisto.shop.layout.head') !!}

    {{-- for extra head data --}}
    @yield('head')

    {{-- seo meta data --}}
    @yield('seo')

    {{-- fav icon --}}
    @if ($favicon = core()->getCurrentChannel()->favicon_url)
        <link rel="icon" sizes="16x16" href="{{ $favicon }}"/>
    @else
        <link rel="icon" sizes="16x16" href="{{ asset('/themes/velocity/assets/images/static/v-icon.png') }}"/>
    @endif

    {{-- all styles --}}
    @include('shop::layouts.styles')

</head>

<body @if (core()->getCurrentLocale() && core()->getCurrentLocale()->direction == 'rtl') class="rtl" @endif>
{!! view_render_event('bagisto.shop.layout.body.before') !!}
<div id="wrapper">
    {{-- main app --}}
    <div id="app">

        <div class="main-container-wrapper main-content-wrapper">

            @section('body-header')
                {{-- top nav which contains currency, locale and login header --}}
                @include('shop::layouts.top-nav.index')

                {!! view_render_event('bagisto.shop.layout.header.before') !!}

                {{-- primary header after top nav --}}
                @include('shop::layouts.header.index')

                {!! view_render_event('bagisto.shop.layout.header.after') !!}

                <div class="col-12 no-padding d-block">
                    {{-- secondary header --}}
                    <header class="row velocity-divide-page vc-header header-shadow active">
                        {{-- mobile header --}}
                        <div class="vc-small-screen container">
                            @include('shop::layouts.header.mobile')
                        </div>
                    </header>

                    <div>
                        <div class="w-100">
                            @yield('full-width-content')
                        </div>
                        <div class="col-12 no-padding content" id="home-right-bar-container">
                            <div class="container px-0">
                                {!! view_render_event('bagisto.shop.layout.content.before') !!}

                                @yield('content-wrapper')

                                {!! view_render_event('bagisto.shop.layout.content.after') !!}
                            </div>
                        </div>
                    </div>
                </div>
            @show
            <div class="main-content w-100">
                <div class="w-100">
                    @yield('full-width-content-top')
                </div>
                <div class="container">
                    {!! view_render_event('bagisto.shop.layout.full-content.before') !!}

                    @yield('full-content-wrapper')

                    {!! view_render_event('bagisto.shop.layout.full-content.after') !!}
                </div>
                <div class="w-100">
                    @yield('full-width-content-bot')
                </div>
            </div>

        </div>

        {{-- overlay loader --}}
        <velocity-overlay-loader></velocity-overlay-loader>
        <div class="go-top">
            <go-top bg-color="#2b348f"></go-top>
        </div>
    </div>

    {{-- footer --}}
    @section('footer')
        {!! view_render_event('bagisto.shop.layout.footer.before') !!}

        @include('shop::layouts.footer.index')

        {!! view_render_event('bagisto.shop.layout.footer.after') !!}
    @show



    {{-- alert container --}}
    <div id="alert-container"></div>
</div>
{{-- all scripts --}}
@include('shop::layouts.scripts')
{{--        {!! view_render_event('bagisto.shop.layout.body.after') !!}--}}
</body>
</html>
