@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin.center.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin.center.title') }}</h1>
            </div>

            <div class="page-action">

                <a href="{{ route('admin.center.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin.center.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.center.index') }}"></datagrid-plus>
        </div>
    </div>

@stop

