@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin.carousel.item.edit-title') }}
@stop

@push('css')
    <style>
        @media only screen and (max-width: 768px) {
            .content-container .content .page-header .page-action button {
                position: relative;
                right: 0px !important;
                top: 0px !important;
            }

            .content-container .content .page-header .page-title .control-group {
                margin-top: 20px !important;
                width: 100% !important;
                margin-left: 0 !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="content">
        @php
            $locale = core()->getRequestedLocaleCode();
        @endphp

        <form method="POST" id="page-form" action="{{ route('admin.carousel.item.update',$carouselItem->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.carousel.item.index') }}'"></i>

                        {{ __('admin.carousel.item.edit-title') }}
                    </h1>

                </div>
                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin.carousel.item.edit-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()
                    <accordian title="{{ __('admin::app.cms.pages.general') }}" :active="true">
                        <div slot="body">
                            <div class="control-group select" :class="[errors.has('carousel_id') ? 'has-error' : '']">
                                <label for="category-id">{{ __('admin::app.category') }}</label>

                                <select type="text" class="control" name="carousel_id" value="{{ old('carousel_id') ?? $carouselItem->carousel_id }}"
                                        data-vv-as="&quot;{{ __('admin::app.category') }}&quot;">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                                    @endforeach
                                </select>

                                <span class="control-error" v-if="errors.has('carousel_id')">@{{ errors.first('carousel_id') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                                <label for="title" class="required">{{ __('admin::app.admin.system.title') }}</label>

                                <input type="text" class="control" name="title" v-validate="'required'" value="{{ old('title') ?? $carouselItem->title }}"
                                       data-vv-as="&quot;{{ __('admin::app.admin.system.title') }}&quot;">

                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('image') ? 'has-error' : '']">

                                @if ($carouselItem->image)
                                    <a href="#">
                                        <img src="{{ Storage::url($carouselItem->image) }}" class="configuration-image"/>
                                    </a>
                                @endif

                                <input type="file" class="control" id="image" name="image"
                                       :class="[errors.has('image') ? 'has-error' : '']"
                                       value="{{ old('image') ?: $carouselItem->image }}" data-vv-as="&quot;تصویر&quot;"
                                       v-validate=""
                                       style="padding-top: 5px;"/>
                                <span class="control-error" v-if="errors.has('image')">@{{ errors.first('image') }}</span>

                                @if ($carouselItem->image)
                                    <div class="control-group" style="margin-top: 5px;">
                                    <span class="checkbox">
                                        <input type="checkbox" id="image_delete" name="image_delete" value="1">

                                        <label class="checkbox-view" for="delete"></label>
                                        {{ __('admin::app.configuration.delete') }}
                                    </span>
                                    </div>
                                @endif
                            </div>
                            <div class="control-group" :class="[errors.has('url') ? 'has-error' : '']">
                                <label for="url" class="required">{{ __('admin.carousel.item.url') }}</label>

                                <input type="text" class="control" name="url" v-validate="'required'" value="{{ old('url') ?? $carouselItem->url }}"
                                       data-vv-as="&quot;{{ __('admin.carousel.item.url') }}&quot;">

                                <span class="control-error" v-if="errors.has('url')">@{{ errors.first('url') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('order') ? 'has-error' : '']">
                                <label for="order" class="required">{{ __('admin::app.admin.system.sort_order') }}</label>

                                <input type="text" class="control" name="order" v-validate="'required'" value="{{ old('order') ?? $carouselItem->order }}"
                                       data-vv-as="&quot;{{ __('admin::app.admin.system.sort_order') }}&quot;">

                                <span class="control-error" v-if="errors.has('order')">@{{ errors.first('order') }}</span>
                            </div>

                        </div>
                    </accordian>


                </div>
            </div>
        </form>
    </div>
@stop
