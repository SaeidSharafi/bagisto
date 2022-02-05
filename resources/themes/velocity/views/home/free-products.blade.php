@php
    $count = core()->getConfigData('catalog.products.homepage.no_of_new_product_homepage');
    $count = $count ? $count : 10;
@endphp

{!! view_render_event('bagisto.shop.new-products.before') !!}

<product-collections
    additional-class="gray"
    count="{{ (int) $count }}"
    product-id="free-products-carousel"
    product-title="{{ __('shop.home.free-products') }}"
    product-route="{{ route('velocity.category.details', ['category-slug' => 'free-products', 'count' => $count]) }}"
    locale-direction="{{ $direction }}"
    no-data-text="{{ __('velocity::app.products.not-available') }}">
</product-collections>

{!! view_render_event('bagisto.shop.new-products.after') !!}
