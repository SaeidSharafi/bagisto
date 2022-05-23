<product-collections
    additional-class="yellow"
    product-id="jobsproducts-carousel"
    category-image="{{asset('images/shop/carousel/jobs.png')}}"
    product-route="{{ route('velocity.category.details', ['category-slug' => 'career-generator', 'count' => 10]) }}"
    locale-direction="{{ $direction }}"
    count="10">
</product-collections>