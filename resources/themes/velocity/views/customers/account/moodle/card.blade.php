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
        class="unset"
        target="_blank"
        href="{{
    $base_url.'/auth/userkey/login.php?key='.$customer->moodle_login_key
    .'&wantsurl=' . $base_url.'/course/view.php?id='.$product->moodle_id }}"
        title="{{ $product->name }}">
        <div class="card grid-card product-card border" style="aspect-ratio: auto">
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


            </div>
        </div>
    </a>


{!! view_render_event('bagisto.shop.products.list.card.after', ['product' => $product]) !!}
