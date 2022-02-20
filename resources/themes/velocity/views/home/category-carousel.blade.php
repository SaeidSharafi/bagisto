<category-carousel
    class="{{ isset($class) ?? '' }}"
    locale-direction="{{ $direction }}"
    category-count="{{ $velocityMetaData ? $velocityMetaData->sidebar_category_count : 10 }}"
    >
</category-carousel>