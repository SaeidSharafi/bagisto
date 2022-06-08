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
            <div class="row remove-padding-margin">
                @foreach($products as $product)

                    @include('shop::customers.account.moodle.card', [
                        'cardClass' => 'category-product-image-container',
                        'item' => $product
                    ])

                @endforeach
            </div>
            @if ($enrollments)

                <div class="account-heading pt-4 pb-2">
                    دوره های سامانه مجازی
                </div>
                <div class="row remove-padding-margin">
                    @foreach($enrollments as $enrollment)
                        @include('shop::customers.account.moodle.card', [
                      'cardClass' => 'category-product-image-container',
                      'item' => $enrollment
                  ])
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}
@endsection