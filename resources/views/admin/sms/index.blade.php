@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin.admin.sms.sms_log') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin.admin.sms.sms_log') }}</h1>
            </div>

        </div>

        <div class="page-content">
            <div class="table-responsive table">
            <table class="table">
            <thead>
            <tr class="table-success text-right">
                <th class="grid_head sortable" scope="col">#</th>
                <th class="grid_head sortable" scope="col">کد بازگشتی</th>
                <th class="grid_head sortable" scope="col">از شماره</th>
                <th class="grid_head sortable" scope="col">به شماره</th>
                <th class="grid_head sortable" scope="col">پترن</th>
                <th class="grid_head sortable" scope="col">محتوا</th>
                <th class="grid_head sortable" scope="col">تاریخ</th>
            </tr>
            </thead>
            <tbody>
            @foreach($logs as $log)
                <tr>
                    <th scope="row">{{ $log->id }}</th>
                    <td>{{ $log->response }}</td>
                    <td>{{ $log->from }}</td>
                    <td>{{ $log->to }}</td>
                    <td>{{ $log->pattern }}</td>
                    <td>{{ $log->content }}</td>
                    <td>{{ $log->created_at->jdate() }}</td>
                </tr>
            @endforeach
            </tbody>
            </table>
            </div>
            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {!! $logs->links() !!}
            </div>
        </div>
    </div>

@stop

