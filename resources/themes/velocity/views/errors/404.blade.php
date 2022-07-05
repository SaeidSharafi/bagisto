@extends('shop::layouts.master')

@section('page_title')
    {{ __('admin::app.error.404.page-title') }}
@stop

@section('body-header')
@endsection

@section('full-content-wrapper')
    <div class="error-page row text-center">
        <div class="col-6">
            <div
                class="col-12 bg-image broken-image"
            ></div>

            <div class="col-12 fs24">
                {{ __('velocity::app.error.page-lost-short') }}
            </div>

            <p class="col-12 fs20">
                {{ __('velocity::app.error.page-lost-description') }}
            </p>
        </div>

        <div class="col-6">
            <div class="w-100">

                @if ($logo = core()->getCurrentChannel()->logo_url)
                    <div
                        class="w-100 velocity-icon bg-image"
                        style="background-image: url('{{ $logo }}')"
                    ></div>
                @else
                    <div class="w-100 velocity-icon bg-image"
                         style="background-image: url('{{ asset('images/logo-text.png') }}');max-width: 200px;background-position: center center;"></div>
                @endif
            </div>

            <a class="row remove-decoration" href="{{ route('shop.home.index') }}">
                <div class="col-12">
                    <span class="custom-circle">></span>
                </div>

                <span class="col-12 fs16">
                    {{ __('velocity::app.error.go-to-home') }}
                </span>
            </a>
        </div>
    </div>

@endsection

@section('footer')
@show
