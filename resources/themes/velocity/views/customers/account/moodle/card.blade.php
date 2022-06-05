@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

@push('css')
    <style type="text/css">
        .list-card .wishlist-icon i {
            padding-left: 10px;
        }

    </style>
@endpush

@php
    if (isset($checkmode) && $checkmode && $toolbarHelper->getCurrentMode() == "list") {
        $list = true;
    }

    if (isset($item)) {
        $productBaseImage = productimage()->getProductImage($item);
    } else {
        $productBaseImage = productimage()->getProductBaseImage($product);
    }

    $totalReviews = $reviewHelper->getTotalReviews($product);
    $avgRatings = ceil($reviewHelper->getAverageRating($product));

    $galleryImages = productimage()->getGalleryImages($product);
    $priceHTML = view('shop::products.price', ['product' => $product])->render();

    $product->__set('priceHTML', $priceHTML);
    $product->__set('avgRating', $avgRatings);
    $product->__set('totalReviews', $totalReviews);
    $product->__set('galleryImages', $galleryImages);
    $product->__set('shortDescription', $product->short_description);
    $product->__set('firstReviewText', trans('velocity::app.products.be-first-review'));
    $product->__set('addToCartHtml', view('shop::products.add-to-cart', [
        'product'           => $product,
        'addWishlistClass'  => ! (isset($list) && $list) ? '' : '',

        'showCompare'       => core()->getConfigData('general.content.shop.compare_option') == "1"
                                ? true : false,

        'btnText'           => null,
        'moveToCart'        => null,
        'addToCartBtnClass' => '',
    ])->render());

@endphp

{!! view_render_event('bagisto.shop.products.list.card.before', ['product' => $product]) !!}
@if (isset($list) && $list)
    <div class="col-12 lg-card-container list-card product-card row">
        <div class="product-image">
            <a
                title="{{ $product->name }}"
                href="{{ route('shop.productOrCategory.index', $product->url_key) }}">

                <img
                    src="{{ $productBaseImage['medium_image_url'] }}"
                    :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`" alt=""/>
            </a>
        </div>

        <div class="product-information">
            <div>
                <div class="product-name">
                    <a
                        href="{{ route('shop.productOrCategory.index', $product->url_key) }}"
                        title="{{ $product->name }}" class="unset">

                        <span class="fs16">{{ $product->name }}</span>
                    </a>

                    @if (isset($additionalAttributes) && $additionalAttributes)
                        @if (isset($item->additional['attributes']))
                            <div class="item-options">

                                @foreach ($item->additional['attributes'] as $attribute)
                                    <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                @endforeach

                            </div>
                        @endif
                    @endif
                </div>

                <div class="product-price">
                    @include ('shop::products.price', ['product' => $product])
                </div>

                @if( $totalReviews )
                    <div class="product-rating">
                        <star-ratings ratings="{{ $avgRatings }}"></star-ratings>
                        <span>{{ $totalReviews }} Ratings</span>
                    </div>
                @endif

                <div class="cart-wish-wrap mt5">
                    @include ('shop::products.add-to-cart', [
                        'addWishlistClass'  => 'pl10',
                        'product'           => $product,
                        'addToCartBtnClass' => 'medium-padding',
                        'showCompare'       => core()->getConfigData('general.content.shop.compare_option') == "1"
                                               ? true : false,
                    ])
                </div>
            </div>
        </div>
    </div>
@else
    <a
        class="unset"
        href="{{ route('shop.productOrCategory.index', $product->url_key) }}"
        title="{{ $product->name }}">
        <div class="card grid-card product-card">
            <div class="product-image-container">
                <img
                    loading="lazy"
                    alt="{{ $product->name }}"
                    src="{{ $productBaseImage['large_image_url'] }}"
                    data-src="{{ $productBaseImage['large_image_url'] }}"
                    class="card-img-top lzy_img"/>
            </div>
            <div class="card-body">
                <h2 class="product-name w-100 no-padding">

                    {{ $product->name }}

                </h2>
                <div class="product-details">
                    <div>
                        <i class="far fa-user-circle"></i>
                        <span>{{$product->teacher_name}}</span>
                    </div>
                </div>

                <div>
                    @include ('shop::products.price', ['product' => $product])
                </div>
            </div>
        </div>
    </a>
@endif

{!! view_render_event('bagisto.shop.products.list.card.after', ['product' => $product]) !!}
