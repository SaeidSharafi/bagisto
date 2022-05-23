<product-collections
    additional-class="gray"
    product-id="free-products-carousel"
    category-image="{{asset('images/shop/carousel/free.png')}}"
    product-route="{{ route('velocity.category.details', ['category-slug' => 'free', 'count' => 10]) }}"
    locale-direction="{{ $direction }}"
    no-data-text="{{ __('velocity::app.products.not-available') }}"
    count="10">
</product-collections>