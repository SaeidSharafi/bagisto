<product-collections
    additional-class="purple"
    product-id="virtual-products-carousel"
    category-image="{{asset('images/shop/carousel/virtual.png')}}"
    product-route="{{ route('velocity.category.details', ['category-slug' => 'virtual', 'count' => 10]) }}"
    locale-direction="{{ $direction }}"
    count="10">
</product-collections>