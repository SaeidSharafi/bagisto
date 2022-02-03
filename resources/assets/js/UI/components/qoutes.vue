<template>
    <slick-carousel
        v-bind="sliderSetting">
        <div  :id="`qoute-${index}`"  v-for="index in 4" v-bind:key="index">
            <div class="qoute-avatar">
                <img src="images/q-avatar.png">
            </div>
            <p class="qoute-text">
                علی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی دعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای
                کاربردی می باشد، کتابهای زیادی دعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی د
            </p>
            <p class="qoute-author">
                <span class="name">علی قربانی</span>
                <span class="role">مدرس شبکه های کامپیوتری</span>
            </p>
        </div>
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
            "arrows": false,
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