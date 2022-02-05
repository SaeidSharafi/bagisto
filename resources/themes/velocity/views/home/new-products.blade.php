@php
    $count = core()->getConfigData('catalog.products.homepage.no_of_new_product_homepage');
    $count = $count ? $count : 10;
@endphp

{!! view_render_event('bagisto.shop.new-products.before') !!}

<product-collections
    additional-class="yellow"
    count="{{ (int) $count }}"
    product-id="new-products-carousel"
    product-title="{{ __('shop::app.home.new-products') }}"
    product-route="{{ route('velocity.category.details', ['category-slug' => 'new-products', 'count' => $count]) }}"
    locale-direction="{{ $direction }}"
    no-data-text="{{ __('velocity::app.products.not-available') }}">
</product-collections>

{!! view_render_event('bagisto.shop.new-products.after') !!}
