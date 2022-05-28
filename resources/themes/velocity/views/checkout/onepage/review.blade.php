<div class="form-container review-checkout-conainer">
    <accordian :title="'{{ __('shop::app.checkout.onepage.summary') }}'" :active="true">
        <div class="form-header mb-1" slot="header">
            <h3 class="fw6 display-inbl">
                {{ __('shop::app.checkout.onepage.summary') }}
            </h3>
            <i class="rango-arrow"></i>
        </div>

        <div slot="body">
            <div class="cart-item-list cart-details pt-0">
                @foreach ($cart->items as $item)
                    @php
                        $productBaseImage = $item->product->getTypeInstance()->getBaseImage($item);
                    @endphp
                <div class="card cart-row mb-2 py-2">
                    <div class="row col-12 no-padding">
                        <div class="col-2 max-sm-img-dimention">
                            <img src="{{ $productBaseImage['medium_image_url'] }}" alt="" />
                        </div>

                        <div class="col-7 no-padding fs16">

                            {!! view_render_event('bagisto.shop.checkout.name.before', ['item' => $item]) !!}

                                <div class="row">
                                    <span class="col-12 fw6">{{ $item->product->name }}</span>
                                </div>

                            {!! view_render_event('bagisto.shop.checkout.name.after', ['item' => $item]) !!}


                            {!! view_render_event('bagisto.shop.checkout.options.before', ['item' => $item]) !!}

                                @if (isset($item->additional['attributes']))
                                    <div class="item-options">

                                        @foreach ($item->additional['attributes'] as $attribute)
                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                        @endforeach

                                    </div>
                                @endif

                            {!! view_render_event('bagisto.shop.checkout.options.after', ['item' => $item]) !!}
                        </div>
                        <div class="product-price checkout fs18 col-3 pl-0">
                            {!!  $item->product->getTypeInstance()->getPriceHtml()!!}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="order-description row fs16 cart-details">
                <div class="col-lg-4 col-md-12">


                    <div class="payment mb20">
                        <div class="decorator">
                            <i class="icon payment-icon"></i>
                        </div>

                        <div class="text">
                            <h4 class="fw6 fs18">
                                {{ core()->getConfigData('sales.paymentmethods.' . $cart->payment->method . '.title') }}
                            </h4>

                            <span>{{ __('shop::app.customer.account.order.view.payment-method') }}</span>
                        </div>
                    </div>

                    <slot name="place-order-btn"></slot>
                </div>

            </div>
        </div>
    </accordian>
</div>
