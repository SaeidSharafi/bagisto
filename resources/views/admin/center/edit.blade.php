@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin.center.edit-title') }}
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

        <form method="POST" id="page-form" action="{{ route('admin.center.update',$center->id) }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.center.index') }}'"></i>

                        {{ __('admin.center.edit-title') }}
                    </h1>

                </div>
                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin.center.edit-btn-title') }}
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

                                <input type="text" class="control" name="title" v-validate="'required'" value="{{ old('title') ?? ($center->title ?? '') }}" data-vv-as="&quot;{{ __('admin::app.admin.system.title') }}&quot;">

                                <span class="control-error" v-if="errors.has('title')">@{{ errors.first('title') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('address') ? 'has-error' : '']">
                                <label for="address" class="required">{{ __('admin.center.address') }}</label>

                                <textarea class="control" name="address" v-validate="'required'" data-vv-as="&quot;{{ __('admin.center.address') }}&quot;">{{ old('address') ?? ($center->address ?? null) }}</textarea>

                                <span class="control-error" v-if="errors.has('address')">@{{ errors.first('address') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                                <label for="phone" class="required">{{ __('admin.center.phone') }}</label>

                                <input type="text" class="control" name="phone" v-validate="'required'" value="{{ old('link') ?? ($center->phone ?? '') }}" data-vv-as="&quot;{{ __('admin.center.phone') }}&quot;">

                                <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('link') ? 'has-error' : '']">
                                <label for="link" class="required">{{ __('admin.center.link') }}</label>

                                <input type="text" class="control" name="link" v-validate="'required'" value="{{ old('link') ?? ($center->link ?? '') }}" data-vv-as="&quot;{{ __('admin.center.link') }}&quot;">

                                <span class="control-error" v-if="errors.has('link')">@{{ errors.first('link') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('order') ? 'has-error' : '']">
                                <label for="order" class="required">{{ __('admin::app.admin.system.sort_order') }}</label>

                                <input type="text" class="control" name="order" v-validate="'required'" value="{{ old('order') ?? ($center->order ?? '') }}" data-vv-as="&quot;{{ __('admin::app.admin.system.sort_order') }}&quot;">

                                <span class="control-error" v-if="errors.has('order')">@{{ errors.first('order') }}</span>
                            </div>

                        </div>
                    </accordian>


                </div>
            </div>
        </form>
    </div>
@stop
