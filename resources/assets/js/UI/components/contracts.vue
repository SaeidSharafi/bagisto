<template>
    <div class="w-100 " :class="localeDirection">
        <slick-carousel
            v-bind="sliderSetting">
            <div
                :id="`slide-${index}`"
                v-for="index in 12">
                <div class="contract-image">
                    <img src="images/contr-1.png" class="w-100">
                </div>
            </div>
        </slick-carousel>
    </div>
</template>

<script>
export default {
    name: "contracts",
    props: {
        additionalClass:{
            type: String,
            default: 'purple'
        },
        localeDirection: {
            String,
            default: 'rtl'
        }
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
        console.log(this.isRtl);
        console.log(!!(this.isRtl));
        this.sliderSetting = {
            "dots": false,
            "arrows": false,
            "autoplay": true,
            "speed": 1000,
            "rtl":(this.localeDirection === 'rtl'),
            "slidesToShow": 10,
            "slidesToScroll": 1,
            "responsive": [
                {
                    "breakpoint": 1200,
                    "settings": {
                        "slidesToShow": 10
                    }
                },
                {
                    "breakpoint": 992,
                    "settings": {
                        "slidesToShow": 8
                    }
                },
                {
                    "breakpoint": 768,
                    "settings": {
                        "slidesToShow": 7
                    }
                },
                {
                    "breakpoint": 600,
                    "settings": {
                        "slidesToShow": 4
                    }
                },
                {
                    "breakpoint": 480,
                    "settings": {
                        "slidesToShow": 3
                    }
                }
            ]
        }
    }
}
</script>

