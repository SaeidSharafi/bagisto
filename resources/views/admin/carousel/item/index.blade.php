@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin.carousel.item.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin.carousel.item.title') }}</h1>
            </div>

            <div class="page-action">

                <a href="{{ route('admin.carousel.item.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin.carousel.item.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.carousel.item.index') }}"></datagrid-plus>
        </div>
    </div>

@stop

