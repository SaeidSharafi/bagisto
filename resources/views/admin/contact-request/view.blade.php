@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin.contactus.view') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    {{ __('admin.contactus.view') }}
                </h1>

            </div>

        </div>

        <div class="page-content">
            <div class="sale-container">
                <div class="sale">
                    <div class="sale-section">
                        <div class="section-content">
                            <div class="row">
                                <span class="title">{{ __('admin.contactus.first_name') }}</span>
                                <span class="value">{{ $contactRequest->first_name }}</span>
                            </div>
                            <div class="row">
                                <span class="title">{{ __('admin.contactus.last_name') }}</span>
                                <span class="value">{{ $contactRequest->last_name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="sale-section">
                        <div class="section-content">
                            <div class="row">
                                <span class="title">{{ __('admin.contactus.phone') }}</span>
                                <span class="value">{{ $contactRequest->phone }}</span>
                            </div>
                            <div class="row">
                                <span class="title">{{ __('admin.contactus.email') }}</span>
                                <span class="value">{{ $contactRequest->email }}</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="sale">
                    <div class="sale-section">
                        <div class="section-content">
                            <div class="row">
                                <span class="title">{{ __('admin.contactus.subject') }}</span>
                                <span class="value">{{ $contactRequest->subject }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="sale-section">
                        <div class="section-content">
                            <div class="row">
                                <span class="title">{{ __('admin.contactus.message') }}</span>
                                <span class="value">{{ $contactRequest->message }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

@stop

