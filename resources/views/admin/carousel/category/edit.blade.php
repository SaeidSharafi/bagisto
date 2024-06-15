@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin.carousel.category.edit-title') }}
@stop

@push('css')
    <style>
    @media only screen and (max-width: 768px){
        .content-container .content .page-header .page-action button {
            position: relative;
            right: 0px !important;
            top: 0px !important;
        }

        .content-container .content .page-header .page-title .control-group {
            margin-top: 20px!important;
            width: 100%!important;
            margin-left: 0!important;
        }
    }
    </style>
@endpush

@section('content')
    <div class="content">
        @php
            $locale = core()->getRequestedLocaleCode();
        @endphp

        <form method="POST" id="page-form" action="{{ route('admin.carousel.category.update',$category->id) }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.carousel.category.index') }}'"></i>

                        {{ __('admin.carousel.category.edit-title') }}
                    </h1>

                </div>
                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin.carousel.category.edit-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()
                    <accordian title="{{ __('admin::app.cms.pages.general') }}" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                                <label for="title" class="required">{{ __('admin::app.admin.system.title') }}</label>

                                <input type="text" class="control" name="title" v-validate="'required'" value="{{ old('title') ?? ($category->title ?? '') }}" data-vv-as="&quot;{{ __('admin::app.admin.system.title') }}&quot;">

                                <span class="control-error" v-if="errors.has('title')">@{{ errors.first('title') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('order') ? 'has-error' : '']">
                                <label for="order" class="required">{{ __('admin::app.admin.system.sort_order') }}</label>

                                <input type="text" class="control" name="order" v-validate="'required'" value="{{ old('order') ?? ($category->order ?? '') }}" data-vv-as="&quot;{{ __('admin::app.admin.system.sort_order') }}&quot;">

                                <span class="control-error" v-if="errors.has('order')">@{{ errors.first('order') }}</span>
                            </div>

                        </div>
                    </accordian>


                </div>
            </div>
        </form>
    </div>
@stop
