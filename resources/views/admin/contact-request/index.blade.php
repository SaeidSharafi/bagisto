@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin.contactus.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin.contactus.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.contact-request.index') }}"></datagrid-plus>
        </div>
    </div>

@stop

