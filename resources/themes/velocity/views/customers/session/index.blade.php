@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.login-form.page-title') }}
@endsection

@section('content-wrapper')
    <div class="auth-content form-container">

        {!! view_render_event('bagisto.shop.customers.login.before') !!}

        <div class="container">
            <div class="col-lg-10 col-md-12 offset-lg-1">

                <div class="body col-12">
                    <div class="form-header">
                        <h3 class="fw6">
                            {{ __('velocity::app.customer.login-form.registered-user')}}
                        </h3>

                        <p class="fs16">
                            {{ __('velocity::app.customer.login-form.form-login-text')}}
                        </p>
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
                                <label for="password" class="mandatory label-style">
                                    {{ __('shop::app.customer.login-form.password') }}
                                </label>

                                <input
                                    type="password"
                                    class="form-style"
                                    name="password"
                                    v-validate="'required'"
                                    value="{{ old('password') }}"
                                    data-vv-as="&quot;{{ __('shop::app.customer.login-form.password') }}&quot;"/>

                                <span class="control-error" v-if="errors.has('password')" v-text="errors.first('password')"></span>
                                <a href="{{route('customer.session.index',['token'=>request()->input('token'),'type' => 'login_by_otp'])}}">
                                    {{__('shop::app.customer.login-form.otp')}}
                                </a>
                                <a href="{{ route('customer.forgot-password.create') }}" >
                                    {{ __('shop::app.customer.login-form.forgot_pass') }}
                                </a>

                            @else
                                <label for="otp" class="mandatory label-style">
                                    {{ __('shop::app.customer.login-form.otp') }}
                                </label>

                                <input
                                    type="text"
                                    class="form-style"
                                    name="otp"
                                    v-validate="'required'"
                                    value="{{ old('otp') }}"
                                    data-vv-as="&quot;{{ __('shop::app.customer.login-form.otp') }}&quot;"/>

                                <span class="control-error" v-if="errors.has('otp')" v-text="errors.first('otp')"></span>
                                <sms-timer http-request="/api/resend-sms"
                                           resend-text="ارسال مجدد"
                                           timer-text="{{__('app.sms-timer')}}"
                                           phone="{{$phone}}"
                                           time="{{$otp_expire}}">
                                </sms-timer>
                            @endif


                            <div class="mt10">
                                @if (Cookie::has('enable-resend'))
                                    @if (Cookie::get('enable-resend') == true)
                                        <a href="{{ route('customer.resend.verification-email', Cookie::get('email-for-resend')) }}">{{ __('shop::app.customer.login-form.resend-verification') }}</a>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="form-group">

                            {!! Captcha::render() !!}

                        </div>

                        {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}

                        <input class="theme-btn" type="submit" value="{{ __('shop::app.customer.login-form.button_title') }}">

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