<template>
    <div class="shimmer-card-container">
        <slick-carousel
            v-bind="sliderSetting">
            <div
                :id="`slide-${index}`"
                v-for="index in shimmerCountInt">
                <div class="shimmer-card">
                    <div class="shimmer-wrapper">
                        <div class="shimmer-product-image animate"></div>
                        <div class="comment animate"></div>
                        <div class="comment animate"></div>
                        <div class="comment animate"></div>
                    </div>
                </div>
            </div>
        </slick-carousel>
    </div>
</template>

<script>
    export default {
        props: {
            'shimmerCount': {
                default: 6,
            },
            localeDirection: {
                String,
                default: 'rtl'
            }
        },

        data: function () {
            return {
                shimmerCountInt: parseInt(this.shimmerCount),
                sliderSetting: {}
            }
        },
        mounted: function () {
            this.sliderSetting = {
                "dots": false,
                "arrows": false,
                "autoplay": false,
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

<style>
    .shimmer-card-container {
        width: 100%;
    }

    .shimmer-card {
        width: 100%;
        margin: 10px;
        padding: 30px 40px;
        border: 2px solid #fff;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
    }

    .shimmer-product-image {
        width: 100%;
        height: 180px;
    }

    .comment {
        height: 10px;
        background: #777;
        margin-top: 20px;
    }

    .shimmer-wrapper {
        width: 0px;
        animation: fullView 0.5s forwards linear;
    }

    @keyframes fullView {
        100% {
            width: 100%;
        }
    }

    .animate {
        animation : shimmer 2s infinite;
        background: linear-gradient(to right, #eff1f3 4%, #e2e2e2 25%, #eff1f3 36%);
        background-size: 1000px 100%;
    }

    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }
</style>
