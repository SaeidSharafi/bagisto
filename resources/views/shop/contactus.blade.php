@extends('shop::layouts.master')
@php
    $channel = core()->getCurrentChannel();
@endphp
@section('page_title')
    درباره ما
@stop
@push('css')
    <style>
        .map-section iframe {
            width: 100%;
            max-height: 300px;
        }
        .title-grafiti{
            max-width: 50px;
        }
        .title{
            border-width: 3px !important;
            border-color: #1e80dc !important;
            font-size: 2.7rem;
        }
    </style>
@endpush
@section('seo')
    <meta name="description" content="تماس با ما"/>
    <meta name="keywords" content="تماس با ما"/>

@stop
@push('css')

@endpush
@section('full-width-content-bot')
    <div class="container py-5">
        <section class="w-100">
            <div class="section-title pt-5 pb-2">
                <h3 class="title border-bottom text-center font-weight-bold pt-2 pb-4">
                    تماس با ما
                </h3>
            </div>
            <div class="map-section w-100">
                <iframe src="https://balad.ir/embed?p=4p98t93p6hUnOX" title="مشاهده «مرکز آموزش شماره ۱ جهاد دانشگاهی» روی نقشه بلد" width="600" height="450" frameborder="0"
                        style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
        </section>
        <section class="w-100">
            <div class="section-title pt-5 pb-2">
                <h3 class="title border-bottom text-center font-weight-bold pt-2 pb-4">
                    دیگر مراکز {{$channel->name}}
                </h3>
            </div>
            <div class="map-section w-100">
                <div class="row">
                    <div class="col-md-12 col-lg-9">
                        <span class="localtion-title font-weight-bold">مرکز</span>:
                        <span class="location-address">آدرس</span>
                    </div>
                    <div class="col-md-12 col-lg-3 d-flex justify-content-center">
                        <a href="#" class="btn btn-info w-100" style="max-width: 230px;">
                            نقشه
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section class="w-100">
            <div class="section-title pt-5 pb-2">
                <h3 class="title border-bottom text-center font-weight-bold pt-2 pb-4">
                    فرم ارتباط با ما
                </h3>
            </div>
            <div class="map-section w-100">
               <form>

               </form>
            </div>
        </section>
    </div>
@endsection