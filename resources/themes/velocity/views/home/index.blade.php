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
            <meta name="title" content="{{ $metaTitle }}"/>
        @endisset

        @isset($metaDescription)
            <meta name="description" content="{{ $metaDescription }}"/>
        @endisset

        @isset($metaKeywords)
            <meta name="keywords" content="{{ $metaKeywords }}"/>
        @endisset
    @endif
@endsection

@push('css')
    @if (! empty($sliderData))
        <link rel="preload" as="image" href="{{ asset('/storage/' . $sliderData[0]['path']) }}">
    @else
        <link rel="preload" as="image" href="{{ asset('/themes/velocity/assets/images/banner.webp') }}">
    @endif

@endpush

@section('content-wrapper')
    <div class="p-3">
        <div class="w-100 d-none d-md-block">
            @include('shop::home.slider')
        </div>
        <div class="row align-items-stretch justify-content-center featured-slider mx-0 d-none">
            <div class="col-6 col-md-5 col-lg-4">
                <div class="slider-side">
                    <a target="_blank" href="{{$velocityMetaData->blog_url}}" class="d-block h-100">
                        <div class="item w-100 position-relative rounded overflow-hidden"
                             style="background-image: url('{{ $velocityMetaData->blog_image
                                                                ? asset('/storage/'.$velocityMetaData->blog_image)
                                                                : asset("/images/temp/futur-gold.png")}}');">
                            <div class="overlay-img">{{$velocityMetaData->blog_title ?: 'طلاسازی'}}</div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-6 col-md-5 col-lg-4">
                <div class="slider-side">
                    @if (!empty($special_product))
                        <a href="/{{$special_product['url_key']}}" class="d-block h-100">
                            <div class="item w-100 rounded overflow-hidden farsi-number"
                                 style="background-image: url('{{ $velocityMetaData->special_image
                                                                ? asset('/storage/'.$velocityMetaData->special_image)
                                                                : asset("/images/temp/special-discount.png")}}');">

                                <div class="item-details">
                                    <span class="title">{{$special_product['short_name']}}</span>

                                    <span class="timer" id="countdown-timer" data-countdown="{{$special_product['special_price_to']}}">
                                    {{$special_product['specialOfferTimeLeft']}}
                                </span>
                                </div>
                            </div>
                        </a>
                    @else

                        <div class="item w-100 rounded overflow-hidden farsi-number"
                             style="background-image: url('{{asset("/images/temp/special-discount.png")}}');">

                            <div class="item-details">
                                <span class="title">بزودی</span>
                                <span class="timer">۰۰:۰۰:۰۰:۰۰</span>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@section('full-content-wrapper')

    <div class="full-content-wrapper py-lg-3 py-md-1">
        {!! view_render_event('bagisto.shop.home.content.before') !!}

        @if ($productCarousels)
            @foreach($productCarousels as $category)
                <div class="w-100 mb-4">
                    @include('shop::home.carousel-products')
                </div>
            @endforeach
        @elseif ($velocityMetaData)
            {!! DbView::make($velocityMetaData)->field('home_page_content')->render() !!}
        @endif

        {{ view_render_event('bagisto.shop.home.content.after') }}
    </div>
    @include('shop::home.category-carousel')
@endsection

@section('full-width-content-bot')
    <div class="container">
        @if ($carousels)
            @foreach($carousels as $carousel)
                @include('shop.carousel', ['carousel' => $carousel])
            @endforeach
        @else
            @include('shop.contracts')
        @endif
    </div>
@endsection

