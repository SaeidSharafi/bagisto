@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin.cms.categories.edit-title') }}
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

        <form method="POST" id="page-form" action="" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.cms.category.index') }}'"></i>

                        {{ __('admin.cms.categories.edit-title') }}
                    </h1>

                </div>
                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin.cms.categories.edit-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()
                    <accordian title="{{ __('admin::app.cms.pages.general') }}" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.cms.pages.page-title') }}</label>

                                <input type="text" class="control" name="name" v-validate="'required'" value="{{ old('name') ?? ($category->name ?? '') }}" data-vv-as="&quot;{{ __('admin::app.cms.pages.page-title') }}&quot;">

                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('{{$locale}}[url_key]') ? 'has-error' : '']">
                                <label for="url-key" class="required">{{ __('admin::app.cms.pages.url-key') }}</label>

                                <input type="text" class="control" name="slug" v-validate="'required'" value="{{ old('slug') ?? $category->slug ?? '' }}" data-vv-as="&quot;{{ __('admin::app.cms.pages.url-key') }}&quot;">

                                <span class="control-error" v-if="errors.has('slug')">@{{ errors.first('slug') }}</span>
                            </div>


                        </div>
                    </accordian>


                </div>
            </div>
        </form>
    </div>
@stop
