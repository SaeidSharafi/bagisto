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
                        {{ __('velocity::app.customer.verify-form.verify')}}
                    </h3>

                    <p class="fs16">
                        {{ __('velocity::app.customer.verify-form.form-verfiy-text',['phone'=>$phone])}}
                    </p>
                    <p class="fs16">
                        {{ __('velocity::app.customer.verify-form.form-verfiy-desc')}}
                    </p>
                    {!! view_render_event('bagisto.shop.customers.signup.before') !!}

                    <form
                        method="post"
                        action="{{ route('customer.sms.verify.complete') }}"
                        @submit.prevent="onSubmit">

                        {{ csrf_field() }}

                        {!! view_render_event('bagisto.shop.customers.signup_form_controls.before') !!}

                        <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                            <input type="hidden" name="phone" value="{{$phone}}">
                            <input type="hidden" name="token" value="{{$token}}">
                            <input
                                type="text"
                                class="form-style"
                                name="ver_code"
                                v-validate="'required'"
                                value="{{ old('ver_code') }}"
                                data-vv-as="&quot;{{ __('shop::app.customer.signup-form.ver_code') }}&quot;" />

                            <span class="control-error" v-if="errors.has('ver_code')" v-text="errors.first('ver_code')"></span>
                        </div>

                        {!! view_render_event('bagisto.shop.customers.signup_form_controls.ver_code.after') !!}

                        <div class="control-group" id="smstimer">
                            <sms-timer http-request="/api/resend-sms"
                                       resend-text="ارسال مجدد"
                                       timer-text="{{__('app.sms-timer')}}"
                                       phone="{{$phone}}"
                                       time="{{$otp_expire}}">
                            </sms-timer>

                        </div>
                        <div class="control-group">

                            {!! Captcha::render() !!}

                        </div>


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