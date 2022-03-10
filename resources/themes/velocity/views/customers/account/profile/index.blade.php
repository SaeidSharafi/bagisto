@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.profile.index.title') }}
@endsection

@push('css')
    <style>
        .account-head {
            height: 50px;
        }
    </style>
@endpush


@section('page-detail-wrapper')
    <div class="account-head mb-0">
        <span class="back-icon">
            <a href="{{ route('customer.account.index') }}">
                <i class="icon icon-menu-back"></i>
            </a>
        </span>
        <span class="account-heading">
            {{ __('shop::app.customer.account.profile.index.title') }}
        </span>

    </div>

    {!! view_render_event('bagisto.shop.customers.account.profile.view.before', ['customer' => $customer]) !!}

    <div class="account-table-content profile-page-content">
        <div class="table">
            <table>
                <tbody>
                {!! view_render_event(
                'bagisto.shop.customers.account.profile.view.table.before', ['customer' => $customer])
                !!}

                <tr>
                    <td>{{ __('shop::app.customer.account.profile.fname') }}</td>
                    <td>{{ $customer->first_name }}</td>
                </tr>

                {!! view_render_event('bagisto.shop.customers.account.profile.view.table.first_name.after', ['customer' => $customer]) !!}

                <tr>
                    <td>{{ __('shop::app.customer.account.profile.lname') }}</td>
                    <td>{{ $customer->last_name }}</td>
                </tr>

                {!! view_render_event('bagisto.shop.customers.account.profile.view.table.last_name.after', ['customer' => $customer]) !!}

                <tr>
                    <td>{{ __('shop::app.customer.account.profile.gender') }}</td>
                    <td>
                        @if ($customer->gender == "Male")
                            مرد
                        @endif
                        @if ($customer->gender == "Female")
                            زن
                        @endif
                        @if ($customer->gender == "Other")
                            -
                        @endif
                    </td>
                </tr>

                {!! view_render_event('bagisto.shop.customers.account.profile.view.table.gender.after', ['customer' => $customer]) !!}

                <tr>
                    <td>{{ __('shop::app.customer.account.profile.dob') }}</td>
                    <td>{{ $customer->date_of_birth ?? '-' }}</td>
                </tr>

                {!! view_render_event('bagisto.shop.customers.account.profile.view.table.date_of_birth.after', ['customer' => $customer]) !!}

                <tr>
                    <td>{{ __('shop::app.customer.account.profile.email') }}</td>
                    <td>{{ $customer->email }}</td>
                </tr>

                {!! view_render_event(
                'bagisto.shop.customers.account.profile.view.table.after', ['customer' => $customer])
                !!}
                </tbody>
            </table>
            <span class="account-action">
            <a href="{{ route('customer.profile.edit') }}" class="theme-btn light unset float-right">
                {{ __('shop::app.customer.account.profile.index.edit') }}
            </a>
        </span>
        </div>

    </div>

    {!! view_render_event('bagisto.shop.customers.account.profile.view.after', ['customer' => $customer]) !!}
@endsection