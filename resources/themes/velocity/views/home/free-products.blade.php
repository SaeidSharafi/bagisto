@php
    $count = core()->getConfigData('catalog.products.homepage.no_of_new_product_homepage');
    $count = $count ? $count : 10;
    $direction = core()->getCurrentLocale()->direction == 'rtl' ? 'rtl' : 'ltr';
@endphp

{!! view_render_event('bagisto.shop.new-products.before') !!}

<product-collections
    additional-class="gray"
    count="{{ (int) $count }}"
    product-id="free-products-carousel"
    product-title="{{ __('shop::app.home.new-products') }}"
    product-route="{{ route('velocity.category.details', ['category-slug' => 'eng', 'count' => $count]) }}"
    locale-direction="{{ $direction }}"
    no-data-text="{{ __('velocity::app.products.not-available') }}">
</product-collections>

{!! view_render_event('bagisto.shop.new-products.after') !!}
