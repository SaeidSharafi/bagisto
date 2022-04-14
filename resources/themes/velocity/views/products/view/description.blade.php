{{--{!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}--}}

        <div class="header mb-3">
            <h5 class="no-margin display-inbl">
                {{ __('shop.product.details') }}
            </h5>

            <i class="rango-arrow"></i>
        </div>

        <div class="body">
            <div class="full-description">
                {!! $product->description !!}
            </div>
        </div>

{!! view_render_event('bagisto.shop.products.view.description.after', ['product' => $product]) !!}