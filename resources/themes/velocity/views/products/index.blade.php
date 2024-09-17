@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')
@inject ('productRepository', 'Webkul\Product\Repositories\ProductRepository')

@extends('shop::layouts.master')

@section('page_title')
    {{ trim($category->meta_title) != "" ? $category->meta_title : $category->name }}
@stop

@section('seo')
    <meta name="description" content="{{ $category->meta_description }}"/>
    <meta name="keywords" content="{{ $category->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.categories.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getCategoryJsonLd($category) !!}
        </script>
    @endif
@stop

@push('css')
    <style type="text/css">

        @media only screen and (max-width: 992px) {
            .main-content-wrapper .vc-header {
                box-shadow: unset;
            }
        }
    </style>
@endpush

@php
    $isProductsDisplayMode = in_array(
        $category->display_mode, [
            null,
            'products_only',
            'products_and_description'
        ]
    );

    $isDescriptionDisplayMode = in_array(
        $category->display_mode, [
            null,
            'description_only',
            'products_and_description'
        ]
    );
    $style = '';

    if ($category->category_banner){
        $url = Storage::url($category->category_banner);
        $style = "background-image: url('$url')";
    }
@endphp

@section('content-wrapper')

    <div class="w-100 category-banner position-relative">
        <div class="w-100 banner-image"
        style="{{$style}}">
        </div>
        <div class="banner-detail">
            <h2 class="fw6 mb10">{{ $category->name }}</h2>

            @if ($isDescriptionDisplayMode)
                @if ($category->description)
                    <div class="category-description">
                        {!! $category->description !!}
                    </div>
                @endif
            @endif
        </div>
        <div class="{{$category->category_banner ? 'filter dark': ''}}"></div>
    </div>
    <category-component></category-component>

@stop

@push('scripts')
    <script type="text/x-template" id="category-template">
        <section class="row velocity-divide-page category-page-wrapper justify-content-center">
            {!! view_render_event('bagisto.shop.productOrCategory.index.before', ['category' => $category]) !!}

            <div class="col-12 {{in_array($category->display_mode, [null, 'products_only', 'products_and_description']) ? 'col-lg-10' : ''}}">
            <div class="category-container">

                @if ($isProductsDisplayMode)
                    <div class="filters-container">
                        <template v-if="products.length >= 0">
                            @include ('shop::products.list.toolbar')
                        </template>
                    </div>

                    <div
                        class="category-block"
                        @if ($category->display_mode == 'description_only')
                        style="width: 100%"
                        @endif>

                        <shimmer-component v-if="isLoading" shimmer-count="5"></shimmer-component>

                        <template v-else-if="products.length > 0">
                            @if ($toolbarHelper->getCurrentMode() == 'grid')
                                <div class="row remove-padding-margin">
                                    <div class="px-2 my-2 product-card-wrapper"
                                         v-for="(product, index) in products">
                                        <product-card
                                            :key="index"
                                            :product="product"
                                            >
                                        </product-card>
                                    </div>
                                </div>
                            @else
                                <div class="product-list">
                                    <product-card
                                        list=true
                                        :key="index"
                                        :product="product"
                                        v-for="(product, index) in products">
                                    </product-card>
                                </div>
                            @endif

                            {!! view_render_event('bagisto.shop.productOrCategory.index.pagination.before', ['category' => $category]) !!}

                            <div class="bottom-toolbar" v-html="paginationHTML"></div>

                            {!! view_render_event('bagisto.shop.productOrCategory.index.pagination.after', ['category' => $category]) !!}
                        </template>

                        <div class="product-list empty" v-else>
{{--                            <h2>{{ __('shop::app.products.whoops') }}</h2>--}}
                            <p>{{ __('shop::app.products.empty') }}</p>
                        </div>
                    </div>
                @endif
            </div>
            </div>
            {!! view_render_event('bagisto.shop.productOrCategory.index.after', ['category' => $category]) !!}
        </section>
    </script>

    <script>
        Vue.component('category-component', {
            template: '#category-template',

            data: function () {
                return {
                    'products': [],
                    'isLoading': true,
                    'paginationHTML': '',
                }
            },

            created: function () {
                this.getCategoryProducts();
            },

            methods: {
                'getCategoryProducts': function () {
                    this.$http.get(`${this.$root.baseUrl}/category-products/{{ $category->id }}${window.location.search}`)
                        .then(response => {
                            this.isLoading = false;
                            this.products = response.data.products;
                            this.paginationHTML = response.data.paginationHTML;
                        })
                        .catch(error => {
                            this.isLoading = false;
                            console.log(this.__('error.something_went_wrong'));
                        })
                }
            }
        })
    </script>
@endpush