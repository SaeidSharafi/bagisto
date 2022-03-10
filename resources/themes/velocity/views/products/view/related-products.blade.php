<?php
$relatedProducts = $product->related_products()->get();
?>

@if ($relatedProducts->count())
    <h6 class="fw6 text-center py-3">
        دوره‌های مرتبط
    </h6>
    <product-collections-slot
        :slides-to-show=1
        additional-class="related-carousel"
        product-id="related-carousel"
        locale-direction="{{ $direction }}"
        :count={{ $relatedProducts->count() }}>
        @foreach ($relatedProducts as $index => $relatedProduct)
                <div slot="slide-{{ $index }}" class="related-slide">
                    @include ('shop::products.list.card', [
                               'product' => $relatedProduct,
                               'addToCartBtnClass' => 'small-padding',
                           ])
                </div>

        @endforeach
    </product-collections-slot>
@endif