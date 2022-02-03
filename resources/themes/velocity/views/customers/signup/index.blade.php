@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.signup-form.page-title') }}
@endsection

@section('content-wrapper')
    <div class="auth-content form-container">
        <div class="container">
            <div class="col-lg-10 col-md-12 offset-lg-1">


                <div class="body col-12">
                    <h3 class="fw6">
                        {{ __('velocity::app.customer.signup-form.become-user')}}
                    </h3>

                    <p class="fs16">
                        {{ __('velocity::app.customer.signup-form.form-sginup-text')}}
                    </p>

                    {!! view_render_event('bagisto.shop.customers.signup.before') !!}

                    <form
                        method="post"
                        action="{{ route('customer.register.create') }}"
                        @submit.prevent="onSubmit">

                        {{ csrf_field() }}

                        {!! view_render_event('bagisto.shop.customers.signup_form_controls.before') !!}

                        <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                            <label for="first_name" class="required label-style">
                                {{ __('shop::app.customer.signup-form.phone') }}
                            </label>

                            <input
                                type="text"
                                class="form-style"
                                name="phone"
                                v-validate="'required|regex:^09([0-9]{9})'"
                                value="{{ old('phone') }}"
                                data-vv-as="&quot;{{ __('shop::app.customer.signup-form.phone') }}&quot;"
                                data-vv-validate-on="submit"/>

                            <span class="control-error" v-if="errors.has('phone')" v-text="errors.first('phone')"></span>
                        </div>
                        <div class="control-group">

                            {!! Captcha::render() !!}

                        </div>

                        @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                            <div class="control-group">
                                <input type="checkbox" id="checkbox2" name="is_subscribed">
                                <span>{{ __('shop::app.customer.signup-form.subscribe-to-newsletter') }}</span>
                            </div>
                        @endif

                        {!! view_render_event('bagisto.shop.customers.signup_form_controls.after') !!}

                        <button class="theme-btn" type="submit">
                            {{ __('shop::app.customer.signup-form.title') }}
                        </button>
                    </form>

                    {!! view_render_event('bagisto.shop.customers.signup.after') !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

{!! Captcha::renderJS() !!}

@endpush