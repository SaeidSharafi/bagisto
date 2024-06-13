<product-collections

    color-code="{{$category->color}}"
    product-id="free-products-carousel"
    category-image="{{$category->image}}"
    product-route="{{ route('velocity.category.details', ['category-slug' => $category->slug, 'count' => 10]) }}"
    locale-direction="{{ $direction }}"
    no-data-text="{{ __('velocity::app.products.not-available') }}"
    count="10">
</product-collections>