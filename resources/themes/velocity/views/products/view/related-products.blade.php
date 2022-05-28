<?php
$relatedProducts = $product->related_products()->get();
$relatedProducts_array = $relatedProducts->chunk(4)->reverse();
?>



@if ($relatedProducts->count())
    <div class="related-products card-box simple-shadow h-100">
        <h5 class="fw6 text-center py-3">
            دوره‌های مرتبط
        </h5>
        <product-collections-slot
            :slides-to-show=1
            additional-class="related-carousel"
            product-id="related-carousel"
            locale-direction="{{ $direction }}"
            :count={{ $relatedProducts_array->count() }}>
            @foreach ($relatedProducts_array as $index => $relatedProduct_3)
                <div slot="slide-{{ $index+1 }}" class="related-slide">
                    @foreach($relatedProduct_3 as $relatedProduct)
                        @include ('shop::products.list.card-horizontal', [
                                   'product' => $relatedProduct,
                                   'addToCartBtnClass' => 'small-padding',
                               ])
                    @endforeach
                </div>

            @endforeach
        </product-collections-slot>
    </div>
@endif