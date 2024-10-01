@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.forgot-password.page_title') }}
@endsection

@section('content-wrapper')
    <div class="auth-content form-container">
        <div class="container">
            <div class="d-flex w-100 auth-box">

                <div class="body m-0 w-100 card">
                    <h5 class="fw6">
                        {{ __('velocity::app.customer.forget-password.recover-password')}}
                    </h5>

                    <p class="label">
                        {{ __('velocity::app.customer.forget-password.recover-password-text')}}
                    </p>

                    {!! view_render_event('bagisto.shop.customers.forget_password.before') !!}

                    <form
                        method="post"
                        action="{{ route('customer.forgot-password.store') }}"
                        @submit.prevent="onSubmit">

                        {{ csrf_field() }}

                        {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

                        <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">

                            <input
                                type="number"
                                inputmode="numeric"
                                class="form-style rounded"
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

                        {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.after') !!}

                        <button class="theme-btn w-100 rounded" type="submit">
                            {{ __('shop::app.customer.forgot-password.submit') }}
                        </button>
                    </form>

                    {!! view_render_event('bagisto.shop.customers.forget_password.after') !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

{!! Captcha::renderJS() !!}

@endpush