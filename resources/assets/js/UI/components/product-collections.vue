<template>
    <div class="w-100">
        <template v-if="hasProduct">
            <div class="row no-gutters carousel-products">
                <div class="col-md-3 col-lg-2 d-none d-md-block">
                    <div class="category-header"
                    :class="additionalClass">
                        <div class="category-title">
                           {{ (isCategory && !productTitle) ? categoryDetails.name : productTitle}}
                        </div>
                        <div class="category-link" v-if="isCategory">
                            <a class="btn btn-outline-light"
                               :href="`${this.baseUrl}/${categoryDetails.url_path}`" >
                                مشاهده همه
                            </a>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <shimmer-component v-if="isLoading"></shimmer-component>
                    <div class="w-100 " :class="localeDirection">
                        <slick-carousel

                            :id="isCategory ? `${categoryDetails.name}-carousel` : productId"
                            v-bind="sliderSetting"
                            v-if="productCollections.length">
                            <div
                                :id="`slide-${index}`"
                                v-for="(product, index) in productCollections">
                                <product-card
                                    :list="list"
                                    :product="product">
                                </product-card>
                            </div>
                        </slick-carousel>
                    </div>
                </div>
            </div>

        </template>
    </div>
</template>

<script>

export default {
    props: {
        additionalClass:{
            type: String,
            default: 'purple'
        },
        count: {
            type: String,
            default: '10'
        },
        productId: {
            type: String,
            default: ''
        },
        productTitle: String,
        productRoute: String,
        localeDirection: String,
        showRecentlyViewed: {
            type: String,
            default: 'false'
        },
        recentlyViewedTitle: String,
        noDataText: String,
    },

    data: function () {
        return {
            hasProduct: true,
            list: false,
            isLoading: true,
            isCategory: false,
            productCollections: [],
            slidesPerPage: 5,
            windowWidth: window.innerWidth,
            sliderSetting: {}
        }
    },

    mounted: function () {
        this.sliderSetting = {
            "dots": false,
            "arrows": false,
            "autoplay": true,
            "speed": 1000,
            "rtl":(this.localeDirection === 'rtl'),
            "slidesToShow": 5,
            "slidesToScroll": 1,
            "responsive": [
                {
                    "breakpoint": 1200,
                    "settings": {
                        "slidesToShow": 5
                    }
                },
                {
                    "breakpoint": 992,
                    "settings": {
                        "slidesToShow": 4
                    }
                },
                {
                    "breakpoint": 768,
                    "settings": {
                        "slidesToShow": 3
                    }
                },
                {
                    "breakpoint": 600,
                    "settings": {
                        "slidesToShow": 2
                    }
                },
                {
                    "breakpoint": 480,
                    "settings": {
                        "slidesToShow": 1
                    }
                }
            ]
        }
        // this.$nextTick(() => {
        //     window.addEventListener('resize', this.onResize);
        // });

        this.getProducts();
        // this.setWindowWidth();
        // this.setSlidesPerPage(this.windowWidth);
    },

    watch: {
        /* checking the window width */
        // windowWidth(newWidth, oldWidth) {
        //     this.setSlidesPerPage(newWidth);
        // }
    },

    methods: {
        /* fetch product collections */
        getProducts: function () {
            this.$http.get(this.productRoute)
                .then(response => {
                    let count = this.count;

                    if (response.data.status && count != 0) {
                        if (response.data.categoryProducts !== undefined) {
                            this.isCategory = true;
                            this.categoryDetails = response.data.categoryDetails;
                            this.productCollections = response.data.categoryProducts;
                        } else {
                            this.productCollections = response.data.products;
                        }
                    } else {
                        this.productCollections = 0;
                    }
                    if (this.productCollections.length <= 0){
                        this.hasProduct =false;
                    }
                    this.isLoading = false;
                })
                .catch(error => {
                    this.isLoading = false;
                    console.log(this.__('error.something_went_wrong'));
                })
        },

        /* waiting for element */
        waitForElement: function (selector, callback) {
            if (jQuery(selector).length) {
                callback();
            } else {
                setTimeout(() => {
                    this.waitForElement(selector, callback);
                }, 100);
            }
        },

        // /* setting window width */
        // setWindowWidth: function () {
        //     let windowClass = this.getWindowClass();
        //
        //     this.waitForElement(windowClass, () => {
        //         this.windowWidth = $(windowClass).width();
        //     });
        // },

        // /* get window class */
        // getWindowClass: function () {
        //     return this.showRecentlyViewed === 'true'
        //         ? '.with-recent-viewed'
        //         : '.without-recent-viewed';
        // },

        // /* on resize set window width */
        // onResize: function () {
        //     this.windowWidth = $(this.getWindowClass()).width();
        // },

        // /* setting slides on the basis of window width */
        // setSlidesPerPage: function (width) {
        //     if (width >= 1200) {
        //         this.slidesPerPage = 6;
        //     } else if (width < 1200 && width >= 992) {
        //         this.slidesPerPage = 5;
        //     } else if (width < 992 && width >= 822) {
        //         this.slidesPerPage = 4;
        //     } else if (width < 822 && width >= 626) {
        //         this.slidesPerPage = 3;
        //     } else {
        //         this.slidesPerPage = 2;
        //     }
        // }
    },

    // /* removing event */
    // beforeDestroy: function () {
    //     window.removeEventListener('resize', this.onResize);
    // },
}
</script>