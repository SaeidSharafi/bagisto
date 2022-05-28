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

    <a
        class="unset py-1 d-block"
        href="{{ route('shop.productOrCategory.index', $product->url_key) }}"
        title="{{ $product->name }}">
        <div class="card grid-card product-card horizontal">
            <div class="product-image-container">
                <img
                    loading="lazy"
                    alt="{{ $product->name }}"
                    src="{{ $productBaseImage['large_image_url'] }}"
                    data-src="{{ $productBaseImage['large_image_url'] }}"
                    class="card-img-top lzy_img"/>
            </div>
            <div class="card-body">
                <h2 class="product-name w-100 pt-2 mb-0">

                    {{ $product->name }}

                </h2>
                <div>
                    @include ('shop::products.price', ['product' => $product])
                </div>
            </div>
        </div>
    </a>


{!! view_render_event('bagisto.shop.products.list.card.after', ['product' => $product]) !!}
