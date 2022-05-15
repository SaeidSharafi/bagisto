@extends('shop::layouts.master')

@section('page_title')
 {{ __('shop::app.customer.reset-password.title') }}
@endsection

@section('content-wrapper')

<div class="auth-content">
    {!! view_render_event('bagisto.shop.customers.reset_password.before') !!}
        <div class="auth-content form-container">
            <div class="container">
                <div class="col-lg-10 col-md-12 offset-lg-1">
                    <div class="heading mb-2">
                        <h2 class="fs24 fw6">
                            {{ __('shop::app.customer.reset-password.title')}}
                        </h2>
                    </div>

                    <div class="body col-12">

                        {!! view_render_event('bagisto.shop.customers.forget_password.before') !!}

                        <form
                            method="POST"
                            @submit.prevent="onSubmit"
                            action="{{ route('customer.reset-password.store') }}">

                            {{ csrf_field() }}

                            <input type="hidden" name="token" value="{{ $token }}">

                            {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

                            <div :class="`form-group ${errors.has('email') ? 'has-error' : ''}`">
                                <label for="phone" class="required label-style mandatory">
                                    {{ __('shop::app.customer.signup-form.phone') }}
                                </label>

                                <input
                                    id="phone"
                                    type="text"
                                    name="phone"
                                    class="form-style"
                                    value="{{ old('phone') }}"
                                    data-vv-as="&quot;{{ __('shop::app.customer.signup-form.phone') }}&quot;"
                                    v-validate="'required'" />

                                <span class="control-error" v-if="errors.has('phone')" v-text="errors.first('phone')"></span>
                            </div>

                            <div :class="`form-group ${errors.has('password') ? 'has-error' : ''}`">
                                <label for="password" class="required label-style mandatory">
                                    {{ __('shop::app.customer.reset-password.password') }}
                                </label>

                                <input
                                    ref="password"
                                    class="form-style"
                                    name="password"
                                    type="password"
                                    data-vv-as="&quot;{{ __('shop::app.customer.reset-password.password') }}&quot;"
                                    v-validate="'required|min:6'" />

                                <span class="control-error" v-if="errors.has('password')" v-text="errors.first('password')"></span>
                            </div>

                            <div :class="`form-group ${errors.has('confirm_password') ? 'has-error' : ''}`">
                                <label for="confirm_password" class="required label-style mandatory">
                                    {{ __('app.velocity.reset-password.confirm-password') }}
                                </label>

                                <input
                                    type="password"
                                    class="form-style"
                                    name="password_confirmation"
                                    data-vv-as="&quot;{{ __('app.velocity.reset-password.confirm-password') }}&quot;"
                                    v-validate="'required|min:6|confirmed:password'" />

                                <span class="control-error" v-if="errors.has('password_confirmation')" v-text="errors.first('password_confirmation')"></span>
                            </div>

                            {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.after') !!}

                            <button class="theme-btn" type="submit">
                                {{ __('shop::app.customer.reset-password.submit-btn-title') }}
                            </button>
                        </form>


                        {!! view_render_event('bagisto.shop.customers.forget_password.after') !!}
                    </div>
                </div>
            </div>
        </div>
    {!! view_render_event('bagisto.shop.customers.reset_password.before') !!}
</div>
@endsection