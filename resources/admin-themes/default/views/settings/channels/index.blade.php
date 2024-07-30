@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.channels.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin.settings.channels.title') }}</h1>
            </div>

        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.channels.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
