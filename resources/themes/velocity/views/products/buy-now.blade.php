{!! view_render_event('bagisto.shop.products.buy_now.before', ['product' => $product]) !!}
<div class="add-to-cart-btn pl0">
<button type="submit" class="theme-btn text-center buynow" {{ ! $product->isSaleable(1) ? 'disabled' : '' }}>
    {{ __('shop.general.register_course') }}
</button>
</div>

{!! view_render_event('bagisto.shop.products.buy_now.after', ['product' => $product]) !!}