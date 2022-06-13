@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.customers.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.customers.customers.title') }}</h1>
            </div>

            <div class="page-action">
                <div style="display: inline-block;">
                    <div class="export-import btn btn-lg" @click="showModal('downloadDataGrid')">
                        <i class="export-icon"></i>

                        <span>
                        {{ __('admin::app.export.export') }}
                    </span>
                    </div>
                    <a href="{{ route('admin.customers.bulk.index') }}" class="export-import btn btn-lg">
                        {{ __('admin.customers.customers.bulk-title') }}
                    </a>
                </div>

                <a href="{{ route('admin.customer.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.customers.customers.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.customer.index') }}"></datagrid-plus>
        </div>
    </div>

    <modal id="downloadDataGrid" :is-open="modalIds.downloadDataGrid">
        <h3 slot="header">{{ __('admin::app.export.download') }}</h3>

        <div slot="body">
            <export-form></export-form>
        </div>
    </modal>
@stop

@push('scripts')
    @include('admin::export.export', ['gridName' => app('Webkul\Admin\DataGrids\CustomerDataGrid')])
@endpush
