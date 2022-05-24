<template>
    <div class="col-12 lg-card-container list-card product-card row" v-if="list">
        <div class="product-image">
            <a :title="product.short_name" :href="`${baseUrl}/${product.slug}`">
                <img
                    :src="product.image || product.product_image"
                    :onerror="`this.src='${this.$root.baseUrl}/vendor/webkul/ui/assets/images/product/large-product-placeholder.png'`"/>

                <product-quick-view-btn :quick-view-details="product" v-if="!isMobile()"></product-quick-view-btn>
            </a>
        </div>

        <div class="product-information">
            <div>
                <div class="product-name">
                    <a :href="`${baseUrl}/${product.slug}`" :title="product.name" class="unset">
                        <span class="fs16">{{ product.short_name }}</span>
                    </a>
                </div>

                <div class="sticker new" v-if="product.new">
                    {{ product.new }}
                </div>

                <div class="product-price" v-html="product.priceHTML"></div>

                <div class="product-rating" v-if="product.totalReviews && product.totalReviews > 0">
                    <star-ratings :ratings="product.avgRating"></star-ratings>
                    <span>{{ __('products.reviews-count', {'totalReviews': product.totalReviews}) }}</span>
                </div>

                <div class="product-rating" v-else>
                    <span class="fs14" v-text="product.firstReviewText"></span>
                </div>

                <vnode-injector :nodes="getDynamicHTML(product.addToCartHtml)"></vnode-injector>
            </div>
        </div>
    </div>

    <div class="card grid-card product-card" v-else>
        <!--        <div class="product-category d-block">-->
        <!--            <a :href="`${baseUrl}/${product.category_slug}`" :title="product.category_name" class="unset" v-if="product.category_slug">-->
        <!--                <span class="category-icon">-->
        <!--                    <i class="fas fa-graduation-cap"></i>-->
        <!--                </span>-->
        <!--                <span class="category-text">{{ product.category_name }}</span>-->
        <!--            </a>-->
        <!--        </div>-->
        <a :href="`${baseUrl}/${product.slug}`" :title="product.short_name" class="d-flex flex-column justify-content-between h-100">
            <div class="product-image-container">
                <img
                    loading="lazy"
                    :alt="product.short_name"
                    :src="product.image || product.product_image"
                    :data-src="product.image || product.product_image"
                    class="card-img-top lzy_img"
                    :onerror="`this.src='${this.$root.baseUrl}/images/product-placeholder.jpg'`"/>
                <!-- :src="`${$root.baseUrl}/vendor/webkul/ui/assets/images/product/meduim-product-placeholder.png`" /> -->
            </div>
            <!--            <product-quick-view-btn :quick-view-details="product"></product-quick-view-btn>-->

            <div class="card-body">
                <div class="product-details">
                    <h2 class="product-name w-100 no-padding">
                        {{ product.short_name }}
                    </h2>
                    <div>
                        <i class="far fa-user-circle"></i>
                        <span>{{ product.teacher_name }}</span>
                    </div>
                </div>
                <!--            <div class="sticker new" v-if="product.new">-->
                <!--                {{ product.new }}-->
                <!--            </div>-->

                <div v-html="product.priceHTML"></div>

                <!--            <div-->
                <!--                class="product-rating col-12 no-padding"-->
                <!--                v-if="product.totalReviews && product.totalReviews > 0">-->

                <!--                <star-ratings :ratings="product.avgRating"></star-ratings>-->
                <!--                <a class="fs14 align-top unset active-hover" :href="`${$root.baseUrl}/reviews/${product.slug}`">-->
                <!--                    {{ __('products.reviews-count', {'totalReviews': product.totalReviews}) }}-->
                <!--                </a>-->
                <!--            </div>-->

                <!--            <div class="product-rating col-12 no-padding" v-else>-->
                <!--                <span class="fs14" v-text="product.firstReviewText"></span>-->
                <!--            </div>-->

                <!--            <vnode-injector :nodes="getDynamicHTML(product.addToCartHtml)"></vnode-injector>-->
                <!--            <a :href="`${baseUrl}/${product.slug}`" class="btn btn-view-product">-->
                <!--                مشاهده دوره-->
                <!--            </a>-->
            </div>
        </a>
    </div>
</template>

<script type="text/javascript">
export default {
    props: [
        'list',
        'product',
    ],

    data: function () {
        return {
            'addToCart': 0,
            'addToCartHtml': '',
        }
    },

    methods: {
        'isMobile': function () {
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                return true;
            } else {
                return false;
            }
        }
    }
}
</script>