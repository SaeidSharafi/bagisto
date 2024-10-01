@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.signup-form.page-title') }}
@endsection

@section('content-wrapper')
    <div class="auth-content form-container">
        <div class="container">
            <div class="d-flex w-100 auth-box">

                <div class="body m-0 w-100 card">
                    <h5 class="fw6">
                        {{ __('velocity::app.customer.signup-form.become-user')}}
                    </h5>

                    <p class="label">
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
                            <input
                                type="number"
                                inputmode="numeric"
                                class="form-style rounded"
                                name="phone"
                                v-validate="{required: true,regex:/^(09|9)([0-9]{9})/}"
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

                        <button class="theme-btn w-100 rounded" type="submit">
                            {{ __('app.velocity.auth-form.authenticate-submit') }}
                        </button>
                    </form>
                    <p class="foot-note pt-3 text-center">
                        ورود شما به معنای پذیرش
                        <a href="/page/terms-conditions" class="text-nowrap">شرایط جهاد دانشگاهی قزوین</a>
                        و
                        <a href="/page/privacy-policy" class="text-nowrap">قوانین حریم‌خصوصی</a>
                        است
                    </p>
                    {!! view_render_event('bagisto.shop.customers.signup.after') !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

{!! Captcha::renderJS() !!}

@endpush