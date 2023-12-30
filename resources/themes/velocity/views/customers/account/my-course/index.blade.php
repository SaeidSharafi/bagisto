@extends('shop::customers.account.index') @section('page_title')
{{ __('shop::app.customer.account.order.index.page-title') }}
@endsection @section('page-detail-wrapper')
<div class="account-head mb-10">
    <span class="back-icon">
        <a href="{{ route('customer.account.index') }}">
            <i class="icon icon-menu-back"></i>
        </a>
    </span>

    <span class="account-heading">
        {{ __('app.customer.account.moodle.index.page-title') }}
    </span>
</div>

{!! view_render_event('bagisto.shop.customers.account.orders.list.before') !!}

<div class="account-items-list customer-orders">
    <div class="account-table-content">
        @if ($offlineCourses->isNotEmpty())
        <div class="mb-4">
            <div
                class="account-heading py-3 px-3"
                style="border-radius: 5pt; background: #2b348f; color: #fff"
            >
                دوره های آموزش حضوری
            </div>
            <div class="row remove-padding-margin">
                @foreach($offlineCourses as $offlineCourse)
                @include('shop::customers.account.my-course.card', [ 'cardClass'
                => 'category-product-image-container', 'item' => $offlineCourse
                ]) @endforeach
            </div>
        </div>
        @endif @if ($moodleCourses)
        <div class="mb-4">
            <div
                class="account-heading py-3 px-3"
                style="border-radius: 5pt; background: #2b348f; color: #fff"
            >
                دوره های سامانه آموزش مجازی
            </div>
            <div class="row remove-padding-margin">
                @foreach($moodleCourses as $moodleCourse)
                @include('shop::customers.account.my-course.card', [ 'cardClass'
                => 'category-product-image-container', 'item' => $moodleCourse
                ]) @endforeach
            </div>
        </div>
        @endif @if ($spots->isNotEmpty())
        <div class="mb-4">
            <div
                class="account-heading py-3 px-3"
                style="border-radius: 5pt; background: #2b348f; color: #fff"
            >
                دوره های آموزش آفلاین
            </div>
            <div class="row remove-padding-margin">
                @foreach($spots as $spot)
                @include('shop::customers.account.my-course.card', [ 'cardClass'
                => 'category-product-image-container', 'item' => $spot ])
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

{!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}
@endsection
