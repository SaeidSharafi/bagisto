<div class="control-group">
    <label>{{ __('shop.admin.category.banner') }}</label>

    @if (isset($category) && $category->category_banner)
        <image-wrapper
            :multiple="false"
            input-name="category_banner"
            :images='"{{ url()->to('/') . '/storage/' . $category->category_banner }}"'
            :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'">
        </image-wrapper>
    @else
        <image-wrapper
            :multiple="false"
            input-name="category_banner"
            :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'">
        </image-wrapper>
    @endif
</div>