@extends('shop::layouts.master')

@section('page_title')
    {{ $page->page_title }}
@endsection

@push('css')
    <style>
        .about-us {
            padding: 40px 10px;
            background: #ebebeb;
        }

        .about-us-wrapper {
            border: 1px solid;
            border-radius: 10px;
            margin: 40px auto;
            max-width: 600px;
            padding: 10px;
            z-index: 2;
        }

        .about-us-wrapper .about-us-content {
            background: #fff;
            border-radius: 10px 10px 0 10px;
            padding: 0;
            position: relative;
            z-index: 6;
            /* overflow: hidden; */
        }

        .about-us-wrapper .content-wrapper {
            padding: 10px;
        }

        .about-us-title {
            position: relative;
        }

        .title-wrapper {
            overflow: hidden;
            padding: 20px 10px 30px 0;
            border-radius: 0 10px 0 0;
        }

        span.title-label {
            background-color: #ffcb05;
            width: 20%;
            height: 22px;
            display: block;
            margin-right: -10px;
            margin-top: -20px;
            border-radius: 0 0 0 10px;
        }

        span.title-text {
            font-size: 2.4rem;
            color: #19499c;
            display: inline-block;
            font-weight: bolder;
            z-index: 3;
            position: relative;
        }

        span.title-text.en {
            color: #c2c2c2;
            font-size: 2rem;
            font-weight: 100;
        }

        .title-particle {
            position: absolute;
            max-width: 95px;
            left: 2%;
            top: -60%;
        }

        @media (min-width: 600px) {
            .title-particle {

                right: 30%;
                left: auto;
            }
        }

        .about-us-bottom {
            background: #00bac6;
            width: 100vw;
            height: 180px;
            transform: translateY(-50%);
            margin-bottom: -100px;
            z-index: 0;
            position: absolute;
            right: 0;
        }
    </style>
@endpush

@section('head')
    @isset($page->meta_title)
        <meta name="title" content="{{ $page->meta_title }}" />
    @endisset

    @isset($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}" />
    @endisset

    @isset($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}" />
    @endisset
@endsection

@section('full-width-content-bot')
    <div class="about-us position-relative">
        <div class="about-us-wrapper">
            <div class="about-us-content">
                <div class="about-us-title">
                    <div class="title-wrapper">
                        <span class="title-label"></span>
                        <span class="title-text">{{$page->page_title}}</span>
                    </div>
                    <div class="title-particle">
                        <img src="{{asset('images/particles.png')}}" class="w-100">
                    </div>
                </div>
                <div class="content-wrapper">
                    {!! DbView::make($page)->field('html_content')->render() !!}
                </div>
            </div>
        </div>
        <div class="about-us-bottom"></div>
    </div>
@endsection