<template>
    <slick-carousel
        v-bind="sliderSetting">
        <slot></slot>
    </slick-carousel>
</template>

<script>
export default {
    name: "qoutes",
    props:{
        localeDirection: {
            String,
            default: 'rtl'
        },
    },
    data: function () {
       return {
           sliderSetting: {}
       }
    },
    mounted: function (){
        this.sliderSetting = {
            "dots": true,
            "arrows": true,
            "autoplay": true,
            "autoplaySpeed": 10000,
            "speed": 1000,
            "rtl":(this.localeDirection === 'rtl'),
            "slidesToShow": 1,
            "slidesToScroll": 1,
        }
    }
}
</script>

<style scoped>

</style>