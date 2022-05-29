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
                        {{ __('velocity::app.customer.verify-form.verify')}}
                    </h5>

                    <p class="label">
                        {{ __('velocity::app.customer.verify-form.form-verfiy-text',['phone'=>$phone])}}
                    </p>
                    <p class="label">
                        {{ __('velocity::app.customer.verify-form.form-verfiy-desc')}}
                    </p>
                    {!! view_render_event('bagisto.shop.customers.signup.before') !!}

                    <form
                        method="post"
                        action="{{ route('customer.sms.verify.complete',['token'=>$token]) }}"
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
                                data-vv-as="&quot;{{ __('shop::app.customer.login-form.otp_field') }}&quot;" />

                            <span class="control-error" v-if="errors.has('ver_code')" v-text="errors.first('ver_code')"></span>
                        </div>

                        {!! view_render_event('bagisto.shop.customers.signup_form_controls.ver_code.after') !!}

                        <div class="label control-group" id="smstimer">
                            <sms-timer http-request="{{route('sms.resend')}}"
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