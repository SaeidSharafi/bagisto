@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin.center.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.center.store') }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.center.index') }}'"></i>

                        {{ __('admin.center.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin.center.create-btn-title') }}
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

                                <input type="text" class="control" name="title" v-validate="'required'" value="{{ old('title') }}" data-vv-as="&quot;{{ __('admin::app.admin.system.title') }}&quot;">

                                <span class="control-error" v-if="errors.has('title')">@{{ errors.first('title') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('address') ? 'has-error' : '']">
                                <label for="address" class="required">{{ __('admin.center.address') }}</label>

                                <textarea class="control" name="address" v-validate="'required'" data-vv-as="&quot;{{ __('admin.center.address') }}&quot;">{{ old('address') }}</textarea>

                                <span class="control-error" v-if="errors.has('address')">@{{ errors.first('address') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                                <label for="phone" class="required">{{ __('admin.center.phone') }}</label>

                                <input type="text" class="control" name="phone" v-validate="'required'" value="{{ old('phone') }}" data-vv-as="&quot;{{ __('admin.center.phone') }}&quot;">

                                <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('link') ? 'has-error' : '']">
                                <label for="link" class="required">{{ __('admin::app.admin.system.link') }}</label>

                                <input type="text" class="control" name="link" v-validate="'required'" value="{{ old('link') }}" data-vv-as="&quot;{{ __('admin::app.admin.system.link') }}&quot;">

                                <span class="control-error" v-if="errors.has('link')">@{{ errors.first('link') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('order') ? 'has-error' : '']">
                                <label for="order" class="required">{{ __('admin::app.admin.system.sort_order') }}</label>

                                <input type="text" class="control" name="order" v-validate="'required'" value="{{ old('order') ?? 1 }}" data-vv-as="&quot;{{ __('admin::app.admin.system.sort_order') }}&quot;">

                                <span class="control-error" v-if="errors.has('order')">@{{ errors.first('order') }}</span>
                            </div>
                        </div>

                    </accordian>


                </div>
            </div>
        </form>
    </div>
@stop

