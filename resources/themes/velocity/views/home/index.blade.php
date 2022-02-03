@extends('shop::layouts.master')

@inject ('productRatingHelper', 'Webkul\Product\Helpers\Review')

@php
    $channel = core()->getCurrentChannel();

    $homeSEO = $channel->home_seo;

    if (isset($homeSEO)) {
        $homeSEO = json_decode($channel->home_seo);

        $metaTitle = $homeSEO->meta_title;

        $metaDescription = $homeSEO->meta_description;

        $metaKeywords = $homeSEO->meta_keywords;
    }
@endphp

@section('page_title')
    {{ isset($metaTitle) ? $metaTitle : "" }}
@endsection

@section('head')
    @if (isset($homeSEO))
        @isset($metaTitle)
            <meta name="title" content="{{ $metaTitle }}" />
        @endisset

        @isset($metaDescription)
            <meta name="description" content="{{ $metaDescription }}" />
        @endisset

        @isset($metaKeywords)
            <meta name="keywords" content="{{ $metaKeywords }}" />
        @endisset
    @endif
@endsection

@push('css')
    @if (! empty($sliderData))
        <link rel="preload" as="image" href="{{ asset('/storage/' . $sliderData[0]['path']) }}">
    @else
        <link rel="preload" as="image" href="{{ asset('/themes/velocity/assets/images/banner.webp') }}">
    @endif

    <style type="text/css">
        .product-price span:first-child, .product-price span:last-child {
            font-size: 18px;
            font-weight: 600;
        }
    </style>
@endpush

@section('content-wrapper')
    <div class="p-3">
    <div class="row align-items-stretch">
        <div class="col-12 col-md-8 col-xl-10 d-none d-md-block">
            @include('shop::home.slider')
        </div>
        <div class="col-md-4 col-xl-2 col-12">
            <div class="slider-side">
            <div class="item w-100 d-none d-md-block position-relative rounded overflow-hidden">
                <img src="{{asset("/images/temp/futur-gold.png")}}" class="w-100 h-100 cover">
                <div class="overlay-img">آینده طلاسازی</div>
            </div>
            <div class="item w-100 rounded overflow-hidden">
                <img src="{{asset("/images/temp/special-discount.png")}}" class="w-100 cover">
                <div class="item-details">
                    <span class="title">دوره تعمیرات موبایل</span>
                    <span class="timer">
                        ۰۱:۰۰:۲۰:۱۰
                    </span>
                </div>

            </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('full-content-wrapper-fluid')

    @include('shop::home.about-us')
    @include('shop::home.category-carousel')
    <div class="full-content-wrapper">
        {!! view_render_event('bagisto.shop.home.content.before') !!}

            @if ($velocityMetaData)
                {!! DbView::make($velocityMetaData)->field('home_page_content')->render() !!}
            @else
                @include('shop::home.advertisements.advertisement-four')
                @include('shop::home.featured-products')
                @include('shop::home.advertisements.advertisement-three')
                @include('shop::home.new-products')
                @include('shop::home.advertisements.advertisement-two')
            @endif

        {{ view_render_event('bagisto.shop.home.content.after') }}
    </div>

@endsection

