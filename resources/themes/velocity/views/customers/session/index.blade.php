@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.login-form.page-title') }}
@endsection

@section('content-wrapper')
    <div class="auth-content form-container">

        {!! view_render_event('bagisto.shop.customers.login.before') !!}

        <div class="container">
            <div class="d-flex w-100 auth-box">


                <div class="body m-0 w-100 card">
                    <div class="form-header">
                        <h5 class="">
                            @if ($type === "login_by_password")
                            {{ __('velocity::app.customer.login-form.form-login-text')}}
                            @else
                                {{ __('shop::app.customer.login-form.form-otp-text')}}
                            @endif
                        </h5>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('customer.session.create') }}"
                        @submit.prevent="onSubmit">

                        {{ csrf_field() }}

                        {!! view_render_event('bagisto.shop.customers.login_form_controls.before') !!}
                        <input type="hidden" name="token" value="{{request()->input('token')}}">

                        <div class="form-group" :class="[errors.has('password') ? 'has-error' : '']">
                            @if ($type === "login_by_password")
                                <div class="mb-2">

                                <input
                                    type="password"
                                    class="form-style rounded"
                                    name="password"
                                    v-validate="'required'"
                                    value="{{ old('password') }}"
                                    data-vv-as="&quot;{{ __('shop::app.customer.login-form.password') }}&quot;"/>

                                <span class="control-error" v-if="errors.has('password')" v-text="errors.first('password')"></span>
                                </div>
                                <a class="label d-block mb-2" href="{{route('customer.session.index',['token'=>request()->input('token'),'type' => 'login_by_otp'])}}">
                                    {{__('shop::app.customer.login-form.otp-link')}}
                                </a>
                                <a class="label" href="{{ route('customer.forgot-password.create') }}" >
                                    {{ __('shop::app.customer.login-form.forgot_pass') }}
                                </a>

                            @else
                                <div class="mb-2">
                                <label for="otp" class="label pb-3">
                                    {{ __('shop::app.customer.login-form.otp',['phone' => $phone]) }}
                                </label>

                                <input
                                    type="text"
                                    class="form-style rounded"
                                    name="otp"
                                    v-validate="'required'"
                                    value="{{ old('otp') }}"
                                    data-vv-as="&quot;{{ __('shop::app.customer.login-form.otp_field') }}&quot;"/>
                                </div>
                                <span class="control-error " v-if="errors.has('otp')" v-text="errors.first('otp')"></span>
                            <div class="label">
                                <sms-timer http-request="{{route('sms.resend')}}"
                                           resend-text="ارسال مجدد"
                                           timer-text="{{__('app.sms-timer')}}"
                                           phone="{{$phone}}"
                                           time="{{$otp_expire}}">
                                </sms-timer>
                            </div>
                            @endif


                            <div class="mt10">
                                @if (Cookie::has('enable-resend'))
                                    @if (Cookie::get('enable-resend') == true)
                                        <a href="{{ route('customer.resend.verification-email', Cookie::get('email-for-resend')) }}">{{ __('shop::app.customer.login-form.resend-verification') }}</a>
                                    @endif
                                @endif
                            </div>
                        </div>

{{--                        <div class="form-group">--}}

{{--                            {!! Captcha::render() !!}--}}

{{--                        </div>--}}

                        {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}

                        <input class="theme-btn w-100 rounded" type="submit" value="{{ __('app.velocity.otp-form.confirm') }}">

                    </form>
                </div>
            </div>
        </div>

        {!! view_render_event('bagisto.shop.customers.login.after') !!}
    </div>
@endsection

@push('scripts')

    {!! Captcha::renderJS() !!}

@endpush