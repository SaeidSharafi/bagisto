<div class="form-container review-checkout-conainer">
    <accordian :title="'{{ __('shop::app.checkout.onepage.summary') }}'" :active="true">
        <div class="form-header mb-1" slot="header">
            <h3 class="fw6 display-inbl">
                {{ __('shop::app.checkout.onepage.summary') }}
            </h3>
            <i class="rango-arrow"></i>
        </div>

        <div slot="body">
            <div class="cart-item-list cart-details pt-0 border-0">
                @foreach ($cart->items as $item)
                    @php
                    $product = $item->product;

                        if ($item->type == 'configurable'){
                        $product = $item->children->first()->product;
                        }
    $productBaseImage = $item->product->getTypeInstance()->getBaseImage($item);
                    @endphp
                <div class="card cart-row mb-2 py-2">
                    <div class="row col-12 no-padding">
                        <div class="col-2 max-sm-img-dimention">
                            <img src="{{ $productBaseImage['medium_image_url'] }}" alt="" />
                        </div>

                        <div class="col-7 no-padding">

                            {!! view_render_event('bagisto.shop.checkout.name.before', ['item' => $item]) !!}

                                <div class="row">
                                    <span class="col-12 fw6">{{ $product->name }}</span>
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
                            {!!  $product->getTypeInstance()->getPriceHtml()!!}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </accordian>
</div>
