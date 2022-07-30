@php
    $count = core()->getConfigData('catalog.products.homepage.no_of_featured_product_homepage');
    $count = $count ? $count : 10;
@endphp

<product-collections
    additional-class="purple"
    product-id="fearured-products-carousel"
    product-title="{{ __('shop::app.home.featured-products') }}"
    product-route="{{ route('velocity.category.details', ['category-slug' => 'featured-products', 'count' => $count]) }}"
    locale-direction="{{ $direction }}"
    count="{{ (int) $count }}">
</product-collections>