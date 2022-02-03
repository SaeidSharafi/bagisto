<template>
    <div class="w-100 " :class="localeDirection">
        <slick-carousel
            v-bind="sliderSetting">
            <div
                :id="`slide-${index}`"
                v-for="index in 10">
                <div class="teacher-card">
                    <div class="teacher-image">
                        <img src="images/teacher-sample.jpg" class="w-100">
                    </div>
                    <div class="teahcer-info">
                        <span class="teacher-honorific">
                            استاد گرامی
                        </span>
                        <span class="teacher-role">
                            طراحی وبسایت
                        </span>
                    </div>
                </div>
            </div>
        </slick-carousel>
    </div>
</template>

<script>
export default {
    name: "teacher-collections",
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
        localeDirection: {
            String,
            default: 'rtl'
        },
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
    }
}
</script>

