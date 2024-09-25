@extends('shop::layouts.master')
@php
    $channel = core()->getCurrentChannel();

    $homeSEO = $channel->home_seo;

    if (isset($homeSEO)) {
        $homeSEO = json_decode($channel->home_seo);

        $metaDescription = $homeSEO->meta_description;

        $metaKeywords = $homeSEO->meta_keywords;
    }
@endphp
@section('page_title')
    تماس با ما
@stop

@section('seo')
    <meta name="title" content="تماس با ما"/>
    <meta name="description" content="{{$metaDescription}}"/>
    <meta name="keywords" content="{{$metaKeywords}}"/>
    <script type="application/ld+json">
        {!! app('Webkul\Product\Helpers\SEO')->getPageJsonLd() !!}
    </script>
@endsection

@push('css')
    <style>
        .map-section iframe {
            width: 100%;
            max-height: 300px;
        }

        .title-grafiti {
            max-width: 50px;
        }

        .title {
            border-width: 3px !important;
            border-color: #1e80dc !important;
            font-size: 2.7rem;
        }

        .location-phone {
            font-family: IRANYEKAN, Source Sans Pro, Helvetica Neue, Helvetica, Arial, sans-serif;
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
        @if ($velocityMetaData->map_iframe)
            <section class="w-100">
                <div class="section-title pt-5 pb-2">
                    <h3 class="title border-bottom text-center font-weight-bold pt-2 pb-4">
                        تماس با ما
                    </h3>
                </div>
                <div class="map-section w-100">
                    {!! $velocityMetaData->map_iframe !!}
                </div>
            </section>
        @endif
        @if ($centers->isNotEmpty())
            <section class="w-100">
                <div class="section-title pt-5 pb-2">
                    <h3 class="title border-bottom text-center font-weight-bold pt-2 pb-4">
                        دیگر مراکز {{$channel->name}}
                    </h3>
                </div>
                <div class="map-section w-100" style="direction: rtl;">
                    @foreach($centers as $center)
                        <div class="row" style="font-size: 1.7rem">
                            <div class="col-md-12 col-lg-9">
                                <span class="localtion-title font-weight-bold">{{$center->title}}</span>:
                                <span class="location-address">{{$center->address}}</span>
                                <span class="location-phone font-weight-bold pr-3">تلفن: </span>
                                <span class="location-phone">{{$center->phone}}</span>
                            </div>
                            <div class="col-md-12 col-lg-3 d-flex justify-content-center">
                                <a href="{{$center->link}}" class="btn btn-info w-100" style="max-width: 230px;">
                                    نقشه راهنما
                                </a>
                            </div>
                        </div>
                    @endforeach

                </div>
            </section>
        @endif

        <section class="w-100">
            <div class="section-title pt-5 pb-2">
                <h3 class="title border-bottom text-center font-weight-bold pt-2 pb-4">
                    فرم ارتباط با ما
                </h3>
            </div>
            <div class="map-section w-100">
                @if (\Session::has('success'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{{ \Session::get('success') }}</li>
                        </ul>
                    </div>
                @endif
                <form action="{{route('shop.contactus.store')}}" method="post" @submit.prevent="onSubmit">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                                <label class="pb-2" for="first_name">{{__('validation.attributes.first_name')}}</label>
                                <input
                                    type="text"
                                    class="form-style rounded"
                                    name="first_name"
                                    v-validate="'required'"
                                    value="{{ old('first_name') }}"
                                    data-vv-as="&quot;{{ __('validation.attributes.first_name') }}&quot;"
                                    data-vv-validate-on="submit"/>

                                <span class="control-error" v-if="errors.has('first_name')" v-text="errors.first('first_name')"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                                <label class="pb-2" for="last_name">{{__('validation.attributes.last_name')}}</label>
                                <input
                                    type="text"
                                    class="form-style rounded"
                                    name="last_name"
                                    v-validate="'required'"
                                    value="{{ old('last_name') }}"
                                    data-vv-as="&quot;{{ __('validation.attributes.last_name') }}&quot;"
                                    data-vv-validate-on="submit"/>

                                <span class="control-error" v-if="errors.has('last_name')" v-text="errors.first('last_name')"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                                <label class="pb-2" for="email">{{__('validation.attributes.email')}}</label>
                                <input
                                    type="text"
                                    class="form-style rounded"
                                    name="email"
                                    v-validate="{required: true, email: true}"
                                    value="{{ old('email') }}"
                                    data-vv-as="&quot;{{ __('validation.attributes.email') }}&quot;"
                                    data-vv-validate-on="submit"/>

                                <span class="control-error" v-if="errors.has('email')" v-text="errors.first('email')"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                                <label class="pb-2" for="phone">{{__('admin.center.phone')}}</label>
                                <input
                                    type="text"
                                    class="form-style rounded"
                                    name="phone"
                                    v-validate="{required: true, regex:/^(0)([0-9]{10})/}"
                                    value="{{ old('phone') }}"
                                    data-vv-as="&quot;{{ __('admin.center.phone') }}&quot;"
                                    data-vv-validate-on="submit"/>

                                <span class="control-error" v-if="errors.has('phone')" v-text="errors.first('phone')"></span>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="control-group" :class="[errors.has('subject') ? 'has-error' : '']">
                                <label class="pb-2" for="message">{{__('shop.contactus.subject')}}</label>
                                <input
                                    type="text"
                                    class="form-style rounded"
                                    name="subject"
                                    v-validate="'required'"
                                    value="{{ old('subject') }}"
                                    data-vv-as="&quot;{{ __('shop.contactus.subject') }}&quot;"
                                    data-vv-validate-on="submit"/>

                                <span class="control-error" v-if="errors.has('subject')" v-text="errors.first('subject')"></span>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="control-group" :class="[errors.has('message') ? 'has-error' : '']">
                                <label class="pb-2" for="message">{{__('shop.contactus.message')}}</label>
                                <textarea
                                    class="form-style rounded"
                                    style="height: 80px"
                                    name="message"
                                    v-validate="'required'"
                                    value="{{ old('message') }}"
                                    data-vv-as="&quot;{{ __('shop.contactus.message') }}&quot;"
                                    data-vv-validate-on="submit"></textarea>


                                <span class="control-error" v-if="errors.has('message')" v-text="errors.first('message')"></span>
                            </div>
                        </div>
                    </div>
                    <div class="m-auto pt-4" style="max-width: 150px;">
                        <button class="theme-btn w-100 rounded" type="submit">
                            {{ __('shop.general.submit') }}
                        </button>
                    </div>

                </form>
            </div>
        </section>
    </div>
@endsection