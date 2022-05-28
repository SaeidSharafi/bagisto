<template>
    <div class="w-100 " :class="[localeDirection,additionalClass]">
        <slick-carousel
            v-bind="sliderSetting">
            <slot
                v-for="index in count"
                :name="`slide-${index}`" :data-text="`slide-${index}`">
            </slot>

        </slick-carousel>
    </div>
</template>

<script>

export default {
    props: {
        additionalClass: {
            type: String,
            default: ''
        },
        count: {
            default: 10
        },
        slidesToShow: {
            default: 5
        },
        localeDirection: String
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
            "arrows": true,
            "autoplay": true,
            "autoplaySpeed": 5000,
            "speed": 1000,
            "rtl": (this.localeDirection === 'rtl'),
            "slidesToShow": this.slidesToShow,
            "slidesToScroll": 1,
            'initialSlide':1,

        }
        console.dir(this.count);
        if (this.slidesToShow !== 1) {
            this.sliderSetting.responsive = [
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




    },


}
</script>