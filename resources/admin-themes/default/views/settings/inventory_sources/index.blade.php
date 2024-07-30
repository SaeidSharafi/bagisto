@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.inventory_sources.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.settings.inventory_sources.title') }}</h1>
            </div>

        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.inventory_sources.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
