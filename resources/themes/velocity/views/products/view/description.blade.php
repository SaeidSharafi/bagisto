{{--{!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}--}}

        <div slot="header">
            <h3 class="no-margin display-inbl">
                {{ __('shop.product.details') }}
            </h3>

            <i class="rango-arrow"></i>
        </div>

        <div slot="body">
            <div class="full-description">
                {!! $product->description !!}
            </div>
        </div>

{!! view_render_event('bagisto.shop.products.view.description.after', ['product' => $product]) !!}