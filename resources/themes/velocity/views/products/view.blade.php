@extends('shop::layouts.master')

@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('customHelper', 'Webkul\Velocity\Helpers\Helper')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

@php
    $total = $reviewHelper->getTotalReviews($product);

    $avgRatings = $reviewHelper->getAverageRating($product);
    $avgStarRating = round($avgRatings);

    $productImages = [];
    $images = productimage()->getGalleryImages($product);
    if (count($images) > 1){
         array_shift($images);
    }

    foreach ($images as $key => $image) {
        array_push($productImages, $image['medium_image_url']);
    }
    $style = '';

    if ($product->banner){
        $url = Storage::url($product->banner);
        $style = "background-image: url('$url')";
    }
     $customAttributeValues = $productViewHelper->getAdditionalData($product);

$teacher = collect($customAttributeValues)->filter( function ($value,$key){
    return $value['group'] =="teacher_detail";
})->pluck('value','code');

$course_details = collect($customAttributeValues)->filter( function ($value,$key){
    return $value['group'] == "course_detail";
});
$course_extra = collect($customAttributeValues)->filter( function ($value,$key){
    return $value['group'] == "course_desc";
});
@endphp

@section('page_title')
    {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
@stop
@section('seo')
    <meta name="description"
          content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) !!}
        </script>
    @endif

    <?php $productBaseImage = productimage()->getProductBaseImage($product, $images); ?>

    <meta name="twitter:card" content="summary_large_image"/>

    {{--    <meta name="twitter:title" content="{{ $product->name }}"/>--}}

    {{--    <meta name="twitter:description" content="{{ $product->description }}"/>--}}

    <meta name="twitter:image:alt" content=""/>

    {{--    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}"/>--}}

    <meta property="og:type" content="og:product"/>

    {{--    <meta property="og:title" content="{{ $product->name }}"/>--}}

    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}"/>

    <meta property="og:description" content="{{ $product->description }}"/>

    <meta property="og:url" content="{{ route('shop.productOrCategory.index', $product->url_key) }}"/>
@stop

@push('css')
    <style>
        .related-products {
            width: 100%;
        }

        .recently-viewed {
            margin-top: 20px;
        }

        .store-meta-images > .recently-viewed:first-child {
            margin-top: 0px;
        }

        .main-content-wrapper {
            margin-bottom: 0px;
        }

        .buynow {
            height: 40px;
            text-transform: uppercase;
        }
    </style>
@endpush
@section('full-width-content')
    <div class="product-banner w-100 position-relative">
        <div class="banner-image" style="{{$style}}"></div>
        <div class="d-flex flex-wrap align-items-center h-100 banner-detail container">
            <div class="px-4 font-weight-bold">
                <span class="course-label"> {{__('shop.course')}}</span>
                <span class="course-name">{{$product->name}}</span>
            </div>
            <div class="px-4">
                <span class="teacher-label"> {{__('shop.teacher')}}</span>
                <span class="teacher-name">{{$teacher['teacher_name']}}</span>
            </div>
        </div>
        @if($product->banner)
            <div class="filter grad z-index-1"></div>
        @endif
    </div>
@endsection
@section('full-content-wrapper')
    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}
    <div class="mb-3">
        <section class="product-detail card-box simple-shadow">
            <div class="layouter">
                <product-view>
                    <div class="form-container">
                        @csrf()

                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">

                        <div class="row">
                            {{-- product-gallery --}}
                            <div class="col-md-6 col-lg-5">
                                <div class="px-3 asdasd">
                                    @include ('shop::products.view.gallery',['images' => $images])
                                </div>
                            </div>

                            {{-- right-section --}}
                            <div class="product-info col-md-6 col-lg-7">
                                <div class="pt-4 px-3">
                                    <h1 class="mb-4 font-weight-bold">{{$product->name}}</h1>
                                    {{-- product-info-section --}}
                                    <div class="attributes">
                                        @include ('shop::products.view.attributes',['active' => true,'customAttributeValues'=>$course_details])
                                        @include ('shop::products.view.stock', ['product' => $product])
                                    </div>
                                    @include ('shop::products.view.configurable-options')

                                    <div class="row price-section no-gutters info align-items-end pt-4">

                                        <div class="col-6 price order-2 order-md-0">
                                            @include ('shop::products.price', ['product' => $product])

                                            @if (Webkul\Tax\Helpers\Tax::isTaxInclusive() && $product->getTypeInstance()->getTaxCategory())
                                                <span>
                                                    {{ __('velocity::app.products.tax-inclusive') }}
                                                </span>
                                            @endif
                                        </div>

                                        @if (count($product->getTypeInstance()->getCustomerGroupPricingOffers()) > 0)
                                            <div class="col-12">
                                                @foreach ($product->getTypeInstance()->getCustomerGroupPricingOffers() as $offers)
                                                    {{ $offers }} </br>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="col-6 product-actions">
                                            @if (core()->getConfigData('catalog.products.storefront.buy_now_button_display'))
                                                @include ('shop::products.buy-now', [
                                                    'product' => $product,
                                                ])
                                            @else
                                                @include ('shop::products.add-to-cart', [
                                                                                            'form' => false,
                                                                                            'product' => $product,
                                                                                            'showCartIcon' => false,
                                                                                            'showCompare' => core()->getConfigData('general.content.shop.compare_option') == "1"
                                                                                                            ? true : false,
                                                                                        ])
                                            @endif


                                        </div>
                                    </div>

                                    {!! view_render_event('bagisto.shop.products.view.short_description.before', ['product' => $product]) !!}


                                    {!! view_render_event('bagisto.shop.products.view.short_description.after', ['product' => $product]) !!}


                                    {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

                                    @if ($product->getTypeInstance()->showQuantityBox() && false)
                                        <div>
                                            <quantity-changer
                                                quantity-text="{{ __('shop::app.products.quantity') }}"></quantity-changer>
                                        </div>
                                    @else
                                        <input type="hidden" name="quantity" value="1">
                                    @endif

                                    {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}


                                    @include ('shop::products.view.downloadable')

                                    @include ('shop::products.view.grouped-products')

                                    @include ('shop::products.view.bundle-options')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-info sticky-price out d-block d-md-none" v-if="isMobile()">
                        <div class="container">
                            <div class="options mb-3">
                                @include ('shop::products.view.configurable-options')
                            </div>
                            <div class="row info align-items-end">

                                <div class="col-6 product-actions">

                                    @include ('shop::products.add-to-cart', [
                                        'form' => false,
                                        'product' => $product,
                                        'showCartIcon' => false,
                                        'showCompare' => core()->getConfigData('general.content.shop.compare_option') == "1"
                                                        ? true : false,
                                    ])
                                </div>
                                <div class="col-6 price ">
                                    @include ('shop::products.price', ['product' => $product])

                                    @if (Webkul\Tax\Helpers\Tax::isTaxInclusive() && $product->getTypeInstance()->getTaxCategory())
                                        <span>
                                                    {{ __('velocity::app.products.tax-inclusive') }}
                                                </span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </product-view>
            </div>
        </section>
        <section class="product-description-section card-box simple-shadow">
            <div class="product-description w-100">
                {{-- product long description --}}
                @include ('shop::products.view.description')
            </div>
        </section>
        <section class="product-extra-detail w-100">
            <div class="row align-items-stretch">
                <div class="col-md-9 ">
                    <div class="extra-attributes card-box simple-shadow h-100">
                        @include ('shop::products.view.extra-attributes',['active' => true,'customAttributeValues'=>$course_extra,'hasOrder' => $hasOrder])
                    </div>
                </div>
                <div class="col-md-3">
                    @include('shop::products.view.related-products')
                </div>
            </div>
        </section>
        <section class="product-footer w-100">
            <div class="row">
                <div class="col-md-9 mb-3">
                    <div class="teacher-info p-2 h-100 card-box simple-shadow">
                        <div class="row h-100 align-items-center">
                            <div class="col-md-2 col-3">
                                <div class="teacher-image rounded-circle overflow-hidden">
                                    @if ($teacher['teacher_image'])
                                        <img src="/storage/{{$teacher['teacher_image']}}" class="w-100">
                                    @else
                                        <img src="/images/teacher-sample.jpg" class="w-100">
                                    @endif
                                </div>
                            </div>
                            <div class="col-9 d-block d-md-none">
                                <h5 class="fw6">
                                    استاد گرامی {{ $teacher['teacher_name'] ?? '' }}
                                </h5>
                            </div>
                            <div class="col-md-10 col-12">
                                <div class="w-100">
                                    <h5 class="d-none d-md-block fw6">
                                        استاد گرامی {{ $teacher['teacher_name'] ?? '' }}
                                    </h5>
                                    <p>
                                        {{$teacher['teacher_bio'] ?? ''}}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="course-cert card-box simple-shadow">
                        <h5 class="py-2 text-center fw6">
                            نمونه گواهی دوره
                        </h5>
                        <div class="w-100 p-3">
                            @if ($product->product->categories->pluck('id')->contains(16))
                                <div>
                                    <img src="{{asset('images/certs/icdl.jpg')}}" class="w-100">
                                </div>
                            @elseif ($product->product->categories->pluck('id')->contains(11))
                                <div>
                                    <img src="{{asset('images/certs/doc.jpg')}}" class="w-100">
                                </div>
                            @elseif ($product->product->categories->pluck('id')->contains(6))
                                <div>
                                    <img src="{{asset('images/certs/zaban.jpg')}}" class="w-100">
                                </div>
                            @else
                                <div>
                                    <img src="{{asset('images/certs/all.jpg')}}" class="w-100">
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

        </section>

    </div>
    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>

    <script type="text/javascript" src="{{ asset('themes/velocity/assets/js/jquery.ez-plus.js') }}"></script>

    <script type='text/javascript' src='https://unpkg.com/spritespin@4.1.0/release/spritespin.js'></script>

    <script type="text/x-template" id="product-view-template">
        <form
            method="POST"
            id="product-form"
            @click="onSubmit($event)"
            data-vv-scope="form-buy"
            action="{{ route('cart.add', $product->product_id) }}">

            <input type="hidden" name="is_buy_now" v-model="is_buy_now">

            <slot v-if="slot"></slot>

            <div v-else>
                <div class="spritespin"></div>
            </div>

        </form>
    </script>

    <script>
        Vue.component('product-view', {
            inject: ['$validator'],
            template: '#product-view-template',
            data: function () {
                return {
                    slot: true,
                    is_buy_now: 0,
                }
            },

            mounted: function () {
                let currentProductId = '{{ $product->url_key }}';
                let existingViewed = window.localStorage.getItem('recentlyViewed');

                if (!existingViewed) {
                    existingViewed = [];
                } else {
                    existingViewed = JSON.parse(existingViewed);
                }

                if (existingViewed.indexOf(currentProductId) == -1) {
                    existingViewed.push(currentProductId);

                    if (existingViewed.length > 3)
                        existingViewed = existingViewed.slice(Math.max(existingViewed.length - 4, 1));

                    window.localStorage.setItem('recentlyViewed', JSON.stringify(existingViewed));
                } else {
                    var uniqueNames = [];

                    $.each(existingViewed, function (i, el) {
                        if ($.inArray(el, uniqueNames) === -1) uniqueNames.push(el);
                    });

                    uniqueNames.push(currentProductId);

                    uniqueNames.splice(uniqueNames.indexOf(currentProductId), 1);

                    window.localStorage.setItem('recentlyViewed', JSON.stringify(uniqueNames));
                }
            },

            methods: {
                onSubmit: function (event) {
                    // console.log(event.target.parent);
                    if (event.target.getAttribute('type') != 'submit')
                        return;

                    event.preventDefault();
                    // console.log(event.target.getAttribute('type'));

                    this.$validator.validateAll('form-buy').then(result => {
                        console.log(result);
                        if (result) {
                            this.is_buy_now = event.target.classList.contains('buynow') ? 1 : 0;

                            setTimeout(function () {
                                document.getElementById('product-form').submit();
                            }, 0);
                        }
                    });
                },
            }
        });

        window.onload = function () {
            var thumbList = document.getElementsByClassName('thumb-list')[0];
            var thumbFrame = document.getElementsByClassName('thumb-frame');
            var productHeroImage = document.getElementsByClassName('product-hero-image')[0];

            if (thumbList && productHeroImage) {
                for (let i = 0; i < thumbFrame.length; i++) {
                    thumbFrame[i].style.height = (productHeroImage.offsetHeight / 4) + "px";
                    thumbFrame[i].style.width = (productHeroImage.offsetHeight / 4) + "px";
                }

                if (screen.width > 720) {
                    thumbList.style.width = (productHeroImage.offsetHeight / 4) + "px";
                    thumbList.style.minWidth = (productHeroImage.offsetHeight / 4) + "px";
                    thumbList.style.height = productHeroImage.offsetHeight + "px";
                }
            }

            window.onresize = function () {
                if (thumbList && productHeroImage) {

                    for (let i = 0; i < thumbFrame.length; i++) {
                        thumbFrame[i].style.height = (productHeroImage.offsetHeight / 4) + "px";
                        thumbFrame[i].style.width = (productHeroImage.offsetHeight / 4) + "px";
                    }

                    if (screen.width > 720) {
                        thumbList.style.width = (productHeroImage.offsetHeight / 4) + "px";
                        thumbList.style.minWidth = (productHeroImage.offsetHeight / 4) + "px";
                        thumbList.style.height = productHeroImage.offsetHeight + "px";
                    }
                }
            }
        };
    </script>
@endpush