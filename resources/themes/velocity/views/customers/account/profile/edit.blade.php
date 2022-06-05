@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.profile.index.title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head mb-15">
        <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>
        <span class="account-heading">{{ __('shop::app.customer.account.profile.index.title') }}</span>

        <span></span>
    </div>
    {!! view_render_event('bagisto.shop.customers.account.profile.edit.before', ['customer' => $customer]) !!}
    <form
        method="POST"
        @submit.prevent="onSubmit"
        action="{{ route('customer.profile.store') }}"
        enctype="multipart/form-data">

        <div class="account-table-content edit-account">

            @csrf

            {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.before', ['customer' => $customer]) !!}

            <div :class="`w-100 mb-2 ${errors.has('first_name') ? 'has-error' : ''}`">
                <label class="w-100 mb-2 mandatory">
                    {{ __('shop::app.customer.account.profile.fname') }}
                </label>

                <div class="w-100 mb-3">
                    <input value="{{old('first_name') ?? $customer->first_name }}" name="first_name" type="text" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.profile.fname') }}&quot;" />
                    <span class="control-error" v-if="errors.has('first_name')" v-text="errors.first('first_name')"></span>
                    <span class="control-error" v-if="{{ $errors->has('first_name') }}" v-text="'{{$errors->first('first_name')}}'"></span>

                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.first_name.after', ['customer' => $customer]) !!}

            <div :class="`w-100 mb-2 ${errors.has('last_name') ? 'has-error' : ''}`">
                <label class="w-100 mb-2 mandatory">
                    {{ __('shop::app.customer.account.profile.lname') }}
                </label>

                <div class="w-100 mb-3">
                    <input value="{{old('last_name') ??  $customer->last_name }}" name="last_name" type="text" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.profile.lname') }}&quot;" />
                    <span class="control-error" v-if="errors.has('last_name')" v-text="errors.first('last_name')"></span>
                    <span class="control-error" v-if="{{ $errors->has('last_name') }}" v-text="'{{$errors->first('last_name')}}'"></span>
                </div>
            </div>

            <div :class="`w-100 mb-2 ${errors.has('national_code') ? 'has-error' : ''}`">
                <label class="w-100 mb-2 mandatory">
                    {{ __('app.customer.account.profile.national_code') }}
                </label>

                <div class="w-100 mb-3">
                    <input value="{{ old('national_code') ??$customer->national_code }}" name="national_code" type="text"
                           v-validate="'required|min:10|max:10'" data-vv-as="&quot;{{ __('app.customer.account.profile.national_code') }}&quot;" />
                    <span class="control-error" v-if="errors.has('national_code')" v-text="errors.first('national_code')"></span>
                    <span class="control-error" v-if="{{ $errors->has('national_code') }}" v-text="'{{$errors->first('national_code')}}'"></span>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.last_name.after', ['customer' => $customer]) !!}

            <div :class="`w-100 mb-2 ${errors.has('gender') ? 'has-error' : ''}`">
                <label class="w-100 mb-2 mandatory">
                    {{ __('shop::app.customer.account.profile.gender') }}
                </label>

                <div class="w-100 mb-3">
                    @php
                        $selectedGender = old('gender') ?? $customer->gender;
                    @endphp
                    <select
                        name="gender"
                        v-validate="'required'"
                        class="control styled-select"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.profile.gender') }}&quot;">
                        <option value=""
                            @if ($selectedGender)
                                selected="selected"
                            @endif>
                            {{ __('admin::app.customers.customers.select-gender') }}
                        </option>
                        <option
                            value="Male"
                            @if ($selectedGender)
                                selected="selected"
                            @endif>
                            {{ __('velocity::app.shop.gender.male') }}
                        </option>

                        <option
                            value="Female"
                            @if ($selectedGender)
                                selected="selected"
                            @endif>
                            {{ __('velocity::app.shop.gender.female') }}
                        </option>
                    </select>

                    <div class="select-icon-container">
                        <span class="select-icon rango-arw-100 mb-2-down"></span>
                    </div>

                    <span class="control-error" v-if="errors.has('gender')" v-text="errors.first('gender')"></span>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.gender.after', ['customer' => $customer]) !!}

            <div :class="`w-100 mb-2 ${errors.has('date_of_birth') ? 'has-error' : ''}`">
                <label class="w-100 mb-2">
                    {{ __('shop::app.customer.account.profile.dob') }}
                </label>

                <div class="w-100 mb-3">
                    <p-date-picker name="date_of_birth"
                                   id="date_of_birth"
                                   max-date="{{now()->subDay()}}"
                                   initial-value="{{  old('date_of_birth') ?? $customer->date_of_birth}}"
                                   placeholder="{{ trans('shop::app.customer.account.profile.dob') }}"></p-date-picker>

                    <span class="control-error" v-if="errors.has('date_of_birth')" v-text="errors.first('date_of_birth')"></span>
                    <span class="control-error" v-if="{{ $errors->has('date_of_birth') }}" v-text="'{{$errors->first('date_of_birth')}}'"></span>

                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.date_of_birth.after', ['customer' => $customer]) !!}

            <div class="w-100 mb-3">
                <label class="w-100 mb-2">
                    {{ __('shop::app.customer.account.profile.email') }}
                </label>

                <div class="w-100 mb-3">
                    <input value="{{ $customer->email }}" name="email" type="text" />
                    <span class="control-error" v-if="errors.has('email')" v-text="errors.first('email')"></span>
                    <span class="control-error" v-if="{{$errors->has('email')}}" v-text="'{{$errors->first('email')}}'"></span>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.email.after', ['customer' => $customer]) !!}

            <div class="w-100 mb-3">
                <label class="w-100 mb-2 mandatory">
                    {{ __('shop::app.customer.account.profile.phone') }}
                </label>

                <div class="w-100 mb-3">
                    <input value="{{ old('phone') ?? $customer->phone }}" name="phone" type="text" v-validate="'required'"/>
                    <span class="control-error" v-if="errors.has('phone')" v-text="errors.first('phone')"></span>
                    <span class="control-error" v-if="{{ $errors->has('phone') }}" v-text="'{{$errors->first('phone')}}'"></span>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.phone.after', ['customer' => $customer]) !!}

            <div :class="`w-100 mb-2 ${errors.has('father_name') ? 'has-error' : ''}`">
                <label class="w-100 mb-2">
                    {{ __('app.customer.account.profile.father_name') }}
                </label>

                <div class="w-100 mb-3">
                    <input value="{{ $customer->father_name }}" name="father_name" type="text"
                           data-vv-as="&quot;{{ __('app.customer.account.profile.father_name') }}&quot;" />
                    <span class="control-error" v-if="errors.has('father_name')" v-text="errors.first('father_name')"></span>
                    <span class="control-error" v-if="{{ $errors->has('father_name') }}" v-text="'{{$errors->first('father_name')}}'"></span>
                </div>
            </div>

            <div :class="`w-100 mb-2 ${errors.has('education_field') ? 'has-error' : ''}`">
                <label class="w-100 mb-2">
                    {{ __('app.customer.account.profile.education_field') }}
                </label>

                <div class="w-100 mb-3">
                    <input value="{{ $customer->education_field }}" name="education_field" type="text"
                           data-vv-as="&quot;{{ __('app.customer.account.profile.education_field') }}&quot;" />
                    <span class="control-error" v-if="errors.has('education_field')" v-text="errors.first('education_field')"></span>
                    <span class="control-error" v-if="{{ $errors->has('education_field') }}" v-text="'{{$errors->first('education_field')}}'"></span>
                </div>
            </div>

            <div class="w-100 mb-2 image-container {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                <label class="w-100 mb-2">
                    {{ __('admin::app.catalog.categories.image') }}
                </label>

                <div class="w-100 mb-3">
                    <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="image" :multiple="false" :images='"{{ $customer->image_url }}"'></image-wrapper>

                    <span class="control-error" v-if="{!! $errors->has('image.*') !!}">
                        @foreach ($errors->get('image.*') as $key => $message)
                            @php echo str_replace($key, 'Image', $message[0]); @endphp
                        @endforeach
                    </span>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.image.after', ['customer' => $customer]) !!}

            @if ($customer->password)
                <div class="w-100 mb-3">
                    <label class="w-100 mb-2">
                        {{ __('velocity::app.shop.general.enter-current-password') }}
                    </label>

                    <div :class="`w-100 mb-2 ${errors.has('oldpassword') ? 'has-error' : ''}`">
                        <input value="" name="oldpassword" type="password" />
                    </div>
                </div>
            @else
                <input value="" name="oldpassword" type="hidden" />
            @endif


            {!! view_render_event('bagisto.shop.customers.account.profile.edit.oldpassword.after', ['customer' => $customer]) !!}

            <div class="w-100 mb-3">
                <label class="w-100 mb-2">
                    {{ __('velocity::app.shop.general.new-password') }}
                </label>

                <div :class="`w-100 mb-2 ${errors.has('password') ? 'has-error' : ''}`">
                    <input
                        value=""
                        name="password"
                        ref="password"
                        type="password"
                        v-validate="'min:6'" />

                    <span class="control-error" v-if="errors.has('password')" v-text="errors.first('password')"></span>
                    <span class="control-error" v-if="{{ $errors->has('password') }}" v-text="'{{$errors->first('password')}}'"></span>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.password.after', ['customer' => $customer]) !!}

            <div class="w-100 mb-3">
                <label class="w-100 mb-2">
                    {{ __('velocity::app.shop.general.confirm-new-password') }}
                </label>

                <div :class="`w-100 mb-2 ${errors.has('password_confirmation') ? 'has-error' : ''}`">
                    <input value="" name="password_confirmation" type="password"
                    v-validate="'min:6|confirmed:password'" data-vv-as="confirm password" />

                    <span class="control-error" v-if="errors.has('password_confirmation')" v-text="errors.first('password_confirmation')"></span>
                    <span class="control-error" v-if="{{ $errors->has('password_confirmation') }}" v-text="'{{$errors->first('password_confirmation')}}'"></span>
                </div>
            </div>

            @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                <div class="control-group">
                    <input type="checkbox" id="checkbox2" name="subscribed_to_news_letter" @if (isset($customer->subscription)) value="{{ $customer->subscription->is_subscribed }}" {{ $customer->subscription->is_subscribed ? 'checked' : ''}} @endif  style="width: auto;">
                    <span>{{ __('shop::app.customer.signup-form.subscribe-to-newsletter') }}</span>
                </div>
            @endif

            {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.after', ['customer' => $customer]) !!}

            <button
                type="submit"
                class="theme-btn mb20">
                {{ __('velocity::app.shop.general.update') }}
            </button>
        </div>
    </form>

    {!! view_render_event('bagisto.shop.customers.account.profile.edit.after', ['customer' => $customer]) !!}
@endsection