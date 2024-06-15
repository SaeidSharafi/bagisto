@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin.carousel.category.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin.carousel.category.title') }}</h1>
            </div>

            <div class="page-action">

                <a href="{{ route('admin.carousel.category.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin.carousel.category.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.carousel.category.index') }}"></datagrid-plus>
        </div>
    </div>

@stop

