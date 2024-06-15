@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin.carousel.item.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.carousel.item.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.carousel.item.index') }}'"></i>

                        {{ __('admin.carousel.item.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin.carousel.item.create-btn-title') }}
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

                                <select type="text" class="control" name="carousel_id" value="{{ old('carousel_id') }}"
                                        data-vv-as="&quot;{{ __('admin::app.category') }}&quot;">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                                    @endforeach
                                </select>

                                <span class="control-error" v-if="errors.has('carousel_id')">@{{ errors.first('carousel_id') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                                <label for="title" class="required">{{ __('admin::app.admin.system.title') }}</label>

                                <input type="text" class="control" name="title" v-validate="'required'" value="{{ old('title') }}"
                                       data-vv-as="&quot;{{ __('admin::app.admin.system.title') }}&quot;">

                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('image') ? 'has-error' : '']">
                                <input type="file" class="control" id="image"
                                       :class="[errors.has('image') ? 'has-error' : '']"
                                       v-validate="'required'"
                                       name="image" value="{{ old('image')}}"
                                       data-vv-as="&quot;تصویر&quot;" style="padding-top: 5px;"/>
                                <span class="control-error" v-if="errors.has('image')">@{{ errors.first('image') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('url') ? 'has-error' : '']">
                                <label for="url" class="required">{{ __('admin.carousel.item.url') }}</label>

                                <input type="text" class="control" name="url" v-validate="'required'" value="{{ old('url') }}"
                                       data-vv-as="&quot;{{ __('admin.carousel.item.url') }}&quot;">

                                <span class="control-error" v-if="errors.has('url')">@{{ errors.first('url') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('order') ? 'has-error' : '']">
                                <label for="order" class="required">{{ __('admin::app.admin.system.sort_order') }}</label>

                                <input type="text" class="control" name="order" v-validate="'required'" value="{{ old('order') ?? 1 }}"
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

