@php
    if(isset($product)){
    $product_flat = $product->product_flats->first( function ($value, $key){
        return ($value->channel == core()->getCurrentChannel()->code && $value->locale == core()->getCurrentLocale()->code);
    }
    );}

@endphp

<accordian :title="'{{ __('admin::app.catalog.products.banner') }}'" :active="false">
    <div slot="body">
        <div class="control-group">
            <label>{{ __('shop.admin.product.banner') }}</label>

            @if (isset($product_flat) && $product_flat->banner)
                <image-wrapper
                    :multiple="false"
                    input-name="banner"
                    :images='"{{ url()->to('/') . '/storage/' . $product_flat->banner }}"'
                    :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'">
                </image-wrapper>
            @else
                <image-wrapper
                    :multiple="false"
                    input-name="banner"
                    :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'">
                </image-wrapper>
            @endif
        </div>
    </div>
</accordian>