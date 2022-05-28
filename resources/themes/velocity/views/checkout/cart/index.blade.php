@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')

@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.cart.title') }}
@stop

@section('content-wrapper')
    <cart-component></cart-component>
@endsection

@push('css')
    <style type="text/css">
        @media only screen and (max-width: 600px) {
            .rango-delete {
                margin-top: 10px;
                margin-left: -10px !important;
            }
        }
    </style>
@endpush

@push('scripts')
    @include('shop::checkout.cart.coupon')

    <script type="text/x-template" id="cart-template">
        <div class="container">
            <section class="cart-details row no-margin col-12">
                <h2 class="fw6 col-12">{{ __('shop::app.checkout.cart.title') }}</h2>

                @if ($cart)
                    <div class="cart-details-header col-lg-8 col-md-12">

                        <div class="cart-content col-12">
                            <form
                                method="POST"
                                @submit.prevent="onSubmit"
                                action="{{ route('shop.checkout.cart.update') }}">

                                <div class="cart-item-list">
                                    @csrf

                                    @foreach ($cart->items as $key => $item)

                                        @php
                                            $productBaseImage = $item->product->getTypeInstance()->getBaseImage($item);
                                            $product = $item->product;

                                            $productPrice = $product->getTypeInstance()->getProductPrices();

                                            if (is_null ($product->url_key)) {
                                                if (! is_null($product->parent)) {
                                                    $url_key = $product->parent->url_key;
                                                }
                                            } else {
                                                $url_key = $product->url_key;
                                            }

                                        @endphp

                                        <div class="card cart-row mb-2 p-2">
                                            <div class="row m-0" >
                                                <div class="col-2 p-0">
                                                    <a
                                                        title="{{ $product->name }}"
                                                        class="product-image-container w-100"
                                                        href="{{ route('shop.productOrCategory.index', $url_key) }}">
                                                        <img
                                                            class="card-img-top"
                                                            alt="{{ $product->name }}"
                                                            src="{{ $productBaseImage['large_image_url'] }}"
                                                            :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`">
                                                    </a>
                                                </div>


                                                <div class="product-details-content col-7 pr0">
                                                    <div class="row item-title no-margin">
                                                        <a
                                                            href="{{ route('shop.productOrCategory.index', $url_key) }}"
                                                            title="{{ $product->name }}"
                                                            class="unset col-12 no-padding fw6 text-black">
                                                            {{ $product->name }}
                                                        </a>
                                                    </div>

                                                    @if (isset($item->additional['attributes']))
                                                        @foreach ($item->additional['attributes'] as $attribute)
                                                            <div class="row col-12 no-padding no-margin display-block item-details">
                                                                <label class="no-margin">
                                                                    {{ $attribute['attribute_name'] }} :
                                                                </label>
                                                                <span>
                                                                {{ $attribute['option_label'] }}
                                                            </span>
                                                            </div>
                                                        @endforeach
                                                    @endif


                                                    @php
                                                        $moveToWishlist = trans('shop::app.checkout.cart.move-to-wishlist');

                                                        $showWishlist = core()->getConfigData('general.content.shop.wishlist_option') == "1" ? true : false;
                                                    @endphp

                                                    <div class="no-padding col-12 cursor-pointer fs16">
                                                        @auth('customer')
                                                            @if ($showWishlist)
                                                                @if ($item->parent_id != 'null' ||$item->parent_id != null)
                                                                    <div @click="removeLink('{{ __('shop::app.checkout.cart.cart-remove-action') }}')" class="alert-wishlist">
                                                                        @include('shop::products.wishlist', [
                                                                            'route' => route('shop.movetowishlist', $item->id),
                                                                            'text' => "<span class='align-vertical-super'>$moveToWishlist</span>"
                                                                        ])
                                                                    </div>
                                                                @else
                                                                    <div @click="removeLink('{{ __('shop::app.checkout.cart.cart-remove-action') }}')" class="alert-wishlist">
                                                                        @include('shop::products.wishlist', [
                                                                            'route' => route('shop.movetowishlist', $item->child->id),
                                                                            'text' => "<span class='align-vertical-super'>$moveToWishlist</span>"
                                                                        ])
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endauth

                                                    </div>
                                                </div>
                                                @if(false)
                                                    <div class="product-quantity col-3 no-padding">
                                                        <quantity-changer
                                                            :control-name="'qty[{{$item->id}}]'"
                                                            quantity="{{ $item->quantity }}"
                                                            quantity-text="{{ __('shop::app.products.quantity') }}">
                                                        </quantity-changer>
                                                    </div>
                                                @else
                                                    <input type="hidden" name="quantity" value="1">
                                                @endif

                                                <div class="product-price fs18 col-3 pl-0">
                                                    <div class="d-flex flex-column h-100 justify-content-between">
                                                        <div class="d-inline-block text-right text-danger">
                                                            <a
                                                                class="unset
                                                                @auth('customer')
                                                                    ml10
                                                                @endauth
                                                                    "
                                                                href="{{ route('shop.checkout.cart.remove', ['id' => $item->id]) }}"
                                                                @click="removeLink('{{ __('shop::app.checkout.cart.cart-remove-action') }}')">

                                                                <span class="rango-delete fs24"></span>
                                                            </a>
                                                        </div>
                                                        <div>
                                                        {!!  $product->getTypeInstance()->getPriceHtml()!!}
                                                        </div>
                                                    </div>
{{--                                                <span class="card-current-price fw6 mr10">--}}

{{--                                                    {{ core()->currency( $item->base_total) }}--}}

{{--                                                </span>--}}
                                                </div>

                                                @if (! cart()->isItemHaveQuantity($item))
                                                    <div class="control-error mt-4 fs16 fw6">
                                                        * {{ __('shop::app.checkout.cart.quantity-error') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>


                                    @endforeach
                                </div>

                                {!! view_render_event('bagisto.shop.checkout.cart.controls.after', ['cart' => $cart]) !!}



                                {!! view_render_event('bagisto.shop.checkout.cart.controls.after', ['cart' => $cart]) !!}
                            </form>
                        </div>

                        @include ('shop::products.view.cross-sells')
                    </div>
                @endif

                {!! view_render_event('bagisto.shop.checkout.cart.summary.after', ['cart' => $cart]) !!}

                    @if ($cart)
                        <div class="col-lg-4 col-md-12 m-0 pt-0 row order-summary-container px-0">
                            @include('shop::checkout.total.summary', ['cart' => $cart])

                        </div>
                    @else
                        <div class="fs16 col-12 empty-cart-message">
                            {{ __('shop::app.checkout.cart.empty') }}
                        </div>

                        <a
                            class="fs16 mt15 col-12 remove-decoration continue-shopping"
                            href="{{ route('shop.home.index') }}">

                            <button type="button" class="theme-btn light mr15 float-right unset">
                                {{ __('shop::app.checkout.cart.continue-shopping') }}
                            </button>
                        </a>
                    @endif

                {!! view_render_event('bagisto.shop.checkout.cart.summary.after', ['cart' => $cart]) !!}

            </section>
        </div>
    </script>

    <script type="text/javascript" id="cart-template">
        (() => {
            Vue.component('cart-component', {
                template: '#cart-template',
                data: function () {
                    return {
                        isMobileDevice: this.isMobile(),
                    }
                },

                methods: {
                    removeLink(message) {
                        if (! confirm(message))
                            event.preventDefault();
                    }
                }
            })
        })()
    </script>
@endpush