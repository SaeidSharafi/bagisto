@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.customers.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.customer.store') }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.customer.index') }}'"></i>

                        {{ __('admin::app.customers.customers.title') }}

                        {{ Config::get('carrier.social.facebook.url') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.customers.customers.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                    {!! view_render_event('bagisto.admin.customers.create.before') !!}

                    <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                        <label for="first_name" class="required">{{ __('admin::app.customers.customers.first_name') }}</label>
                        <input type="text" class="control" id="first_name" name="first_name" v-validate="'required'" value="{{ old('first_name') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.firstname') }}&quot;">
                        <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                    </div>

                    {!! view_render_event('bagisto.admin.customers.create.first_name.after') !!}

                    <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                        <label for="last_name" class="required">{{ __('admin::app.customers.customers.last_name') }}</label>
                        <input type="text" class="control" id="last_name" name="last_name" v-validate="'required'" value="{{ old('last_name') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.lastname') }}&quot;">
                        <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                    </div>

                    {!! view_render_event('bagisto.admin.customers.create.last_name.after') !!}

                    <div class="control-group" :class="[errors.has('national_code') ? 'has-error' : '']">
                        <label for="national_code">
                            {{ __('app.customer.account.profile.national_code') }}
                        </label>

                            <input value="{{ old('national_code')}}" name="national_code" type="text"
                                   class="control" v-validate="'required|numeric|min:8|max:20'" data-vv-as="&quot;{{ __('app.customer.account.profile.national_code') }}&quot;" />
                            <span class="control-error" v-if="errors.has('national_code')" v-text="errors.first('national_code')"></span>
                    </div>
                    <div class="control-group">
                        <label for="is_moodle_user" class="">{{ __('admin.customers.customers.is_foriegn') }}</label>

                        <label class="switch">
                            <input
                                type="checkbox"
                                id="is_foreign"
                                name="is_foreign">

                            <span class="slider round"></span>
                        </label>

                        <span class="control-error" v-if="errors.has('is_foreign')">@{{ errors.first('is_foreign') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                        <label for="email" class="required">{{ __('shop::app.customer.signup-form.email') }}</label>
                        <input type="email" class="control" id="email" name="email" v-validate="'required|email'" value="{{ old('email') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.email') }}&quot;">
                        <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                    </div>

                    {!! view_render_event('bagisto.admin.customers.create.email.after') !!}

                    <div class="control-group" :class="[errors.has('gender') ? 'has-error' : '']">
                        <label for="gender" class="required">{{ __('admin::app.customers.customers.gender') }}</label>
                        <select name="gender" class="control" id="gender" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.customers.customers.gender') }}&quot;">
                            <option value="">{{ __('admin::app.customers.customers.select-gender') }}</option>
                            <option value="Male">{{ __('admin::app.customers.customers.male') }}</option>
                            <option value="Female">{{ __('admin::app.customers.customers.female') }}</option>
                        </select>
                        <span class="control-error" v-if="errors.has('gender')">@{{ errors.first('gender') }}</span>
                    </div>

                    {!! view_render_event('bagisto.admin.customers.create.gender.after') !!}

                    <div class="control-group" :class="[errors.has('date_of_birth') ? 'has-error' : '']">
                        <label for="dob">{{ __('admin::app.customers.customers.date_of_birth') }}</label>
                        <div class="control">
                        <p-date-picker name="date_of_birth"
                                       id="date_of_birth"
                                       max-date="{{now()->subDay()}}"
                                       initial-value="{{  old('date_of_birth')}}"
                                       v-validate=""
                                       data-vv-as="&quot;{{ __('admin::app.customers.customers.date_of_birth') }}&quot;"
                                       placeholder="{{ __('admin::app.customers.customers.date_of_birth') }}"></p-date-picker>
                        </div>
                        <span class="control-error" v-if="errors.has('date_of_birth')">@{{ errors.first('date_of_birth') }}</span>
                    </div>

                    {!! view_render_event('bagisto.admin.customers.create.date_of_birth.after') !!}

                    <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                        <label for="phone">{{ __('admin::app.customers.customers.phone') }}</label>
                        <input type="text" class="control" id="phone" name="phone" value="{{ old('phone') }}" v-validate="'numeric'" data-vv-as="&quot;{{ __('admin::app.customers.customers.phone') }}&quot;">
                        <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                    </div>

                    {!! view_render_event('bagisto.admin.customers.create.phone.after') !!}

                    <div class="control-group"  :class="[errors.has('father_name') ? 'has-error' : '']">
                        <label for="father_name">{{ __('app.customer.account.profile.father_name') }}</label>

                            <input name="father_name" class="control" type="text" value="{{ old('father_name') }}" data-vv-as="&quot;{{ __('app.customer.account.profile.father_name') }}&quot;" />
                            <span class="control-error" v-if="errors.has('father_name')" v-text="errors.first('father_name')"></span>
                            <span class="control-error" v-if="{{ $errors->has('father_name') }}" v-text="'{{$errors->first('father_name')}}'"></span>

                    </div>

                    <div  class="control-group"  :class="[errors.has('education_field') ? 'has-error' : '']">
                        <label for="education_field">
                            {{ __('app.customer.account.profile.education_field') }}
                        </label>

                            <input value="{{old('education_field') }}" name="education_field" type="text"
                                   class="control" data-vv-as="&quot;{{ __('app.customer.account.profile.education_field') }}&quot;" />
                            <span class="control-error" v-if="errors.has('education_field')" v-text="errors.first('education_field')"></span>
                            <span class="control-error" v-if="{{ $errors->has('education_field') }}" v-text="'{{$errors->first('education_field')}}'"></span>

                    </div>
                    <div class="control-group">
                        <label for="is_moodle_user" class="required">{{ __('admin.customers.customers.is_moodle_user') }}</label>

                        <label class="switch">
                            <input
                                type="checkbox"
                                id="is_moodle_user"
                                name="is_moodle_user">

                            <span class="slider round"></span>
                        </label>

                        <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                    </div>
                    <div class="control-group">
                        <label for="customerGroup" >{{ __('admin::app.customers.customers.customer_group') }}</label>
                        <select  class="control" id="customerGroup" name="customer_group_id">
                        @foreach ($customerGroup as $group)
                                <option value="{{ $group->id }}"> {{ $group->name}} </option>
                        @endforeach
                        </select>
                    </div>

                    {!! view_render_event('bagisto.admin.customers.create.after') !!}
                </div>
            </div>
        </form>
    </div>
@stop