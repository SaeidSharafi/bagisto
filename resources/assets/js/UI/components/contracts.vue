<template>
    <div class="w-100 " :class="localeDirection">
        <slick-carousel
            v-bind="sliderSetting">
            <template v-if="items">
                <div
                    :id="`slide-${index}`"
                    v-for="(item,index) in items">
                    <div class="contract-image">
                        <img :src="item.image" class="w-100" :alt="item.title">
                    </div>
                </div>
            </template>
            <template v-else>
                <div
                    :id="`slide-${index}`"
                    v-for="index in 21">
                    <div class="contract-image">
                        <img :src="`images/shop/contracts/cont${index}.jpg`" class="w-100">
                    </div>
                </div>
            </template>

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
        },
        title:{
            type: String,
            default: 'title'
        },
        items:{
            type: Array,
            default: null
        }
    },
    data: function () {
        return {
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

