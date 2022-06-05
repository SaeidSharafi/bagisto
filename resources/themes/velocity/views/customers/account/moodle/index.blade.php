@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.order.index.page-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head mb-10">
        <span class="back-icon">
            <a href="{{ route('customer.account.index') }}">
                <i class="icon icon-menu-back"></i>
            </a>
        </span>

        <span class="account-heading">
          {{__('app.customer.account.moodle.index.page-title')}}
        </span>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.orders.list.before') !!}

    <div class="account-items-list customer-orders">
        <div class="account-table-content">
            @foreach($products as $product)
                <div class="product-card card">
                    <div class="px-2 my-2 product-card-wrapper">
                        @include('shop::customers.account.moodle.card', [
                            'cardClass' => 'category-product-image-container',
                            'product' => $product,
                            'base_url' => $base_url
                        ])
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}
@endsection