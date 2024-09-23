@inject ('wishListHelper', 'Webkul\Customer\Helpers\Wishlist')

@php
    if (!$images){
        $images = productimage()->getGalleryImages($product);
    }
    $videos = productvideo()->getVideos($product);

    $videoData = $imageData = [];

    foreach ($videos as $key => $video) {
        $videoData[$key]['type'] = $video['type'];
        $videoData[$key]['large_image_url'] = $videoData[$key]['small_image_url']= $videoData[$key]['medium_image_url']= $videoData[$key]['original_image_url'] = $video['video_url'];
    }

    foreach ($images as $key => $image) {
        $imageData[$key]['type'] = '';
        $imageData[$key]['image_alt']          = $image['image_alt'];
        $imageData[$key]['large_image_url']    = $image['large_image_url'];
        $imageData[$key]['small_image_url']    = $image['small_image_url'];
        $imageData[$key]['medium_image_url']   = $image['medium_image_url'];
        $imageData[$key]['original_image_url'] = $image['original_image_url'];
    }

    $images = array_merge($imageData, $videoData);
@endphp

{!! view_render_event('bagisto.shop.products.view.gallery.before', ['product' => $product]) !!}


<product-gallery></product-gallery>


{!! view_render_event('bagisto.shop.products.view.gallery.after', ['product' => $product]) !!}

<script type="text/x-template" id="product-gallery-template">
    <div class="product-image-gallery">
        <div class="prodcut-images-main asd" :title="images.length">

            <slick-carousel
                    ref="galleryCarousel"
                    style="max-height: 350px"
                    id="product-gallery-carousel"
                    v-bind="sliderSetting"
                    @beforeChange="changeThumb"
                    v-if="images.length">
                <div
                        :id="`slide-${index}`"
                        :class="`main-image img-main-${index}`"
                        v-bind:key="index"
                        v-for="(image, index) in images">
                    <video v-if="image.type == 'videos'" width="100%" preload="none" controls>
                        <source :src="image.large_image_url" type="video/mp4">
                    </video>
                    <img loading="lazy" v-else
                         :alt="image.image_alt"
                         :src="image.large_image_url">
                </div>
            </slick-carousel>
        </div>

        <div class="carousel-pagination d-md-none" v-if="thumbs.length > 1">
            <ul>
                <li v-for="(thumb, index) in thumbs" v-bind:key="index" @click="showImage(index)"
                    :class="`carousel-dot ${'dot-'+index} ${index === 0 ? 'is-active' : ''}`">
                </li>
            </ul>
        </div>
        <ul :class="`thumb-list w-100 row ltr d-none d-md-block ${thumbs.length < 5 ? 'is-rtl' : ''}`">
            <slick-carousel
                    ref="thumbsCarousel"
                    style="max-height: 350px"
                    id="product-gallery-carousel"
                    v-bind="thumbnailSetting"
                    v-if="thumbs.length">
                <div v-for="(thumb, index) in thumbs" v-bind:key="index"
                     :id="`thumb-${index}`">
                    <li
                            @click="showImage(index)"
                            :class="`thumb-frame ${index + 1 == 4 ? '' : 'mr5'} ${thumb.large_image_url == currentLargeImageUrl ? 'active' : ''} ${'thumb-'+index} ${index == 0 ? 'is-active' : ''}`"
                    >
                        <div v-if="thumb.type == 'videos'"
                             class="bg-video"
                        >
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div v-else
                             class="bg-image"
                             :style="`background-image: url(${thumb.small_image_url})`">
                            <img :src="thumb.small_image_url" class="w-100" style="object-fit: contain"
                                 :alt="thumb.image_alt"/>
                        </div>
                    </li>
                </div>
            </slick-carousel>

        </ul>
    </div>
</script>

@push('scripts')
    <script type="text/javascript">
        (() => {
            var galleryImages = @json($images);

            Vue.component('product-gallery', {
                template: '#product-gallery-template',
                data: function () {
                    return {
                        images: galleryImages,
                        thumbs: [],
                        galleryCarouselId: 'product-gallery-carousel',
                        currentLargeImageUrl: '',
                        currentLargeImageIndex: 0,
                        currentOriginalImageUrl: '',
                        currentType: '',
                        usingThumb: false,
                        counter: {
                            up: 0,
                            down: 0,
                        },
                        sliderSetting: {
                            "dots": false,
                            "arrows": false,
                            "autoplay": false,
                            "infinite": false,
                            "autoplaySpeed": 5000,
                            "speed": 1000,
                            "rtl": true,
                            "slidesToShow": 1,
                            "slidesToScroll": 1,
                            'initialSlide': 0,

                        }, thumbnailSetting: {
                            "dots": false,
                            "arrows": true,
                            "autoplay": false,
                            "infinite": false,
                            "autoplaySpeed": 5000,
                            "speed": 1000,
                            "rtl": true,
                            "slidesToShow": 4,
                            "slidesToScroll": 4,
                            'initialSlide': 0,

                        }
                    }
                },

                watch: {
                    'images': function (newVal, oldVal) {
                        // if (this.images[0]) {
                        //     this.changeImage({
                        //         largeImageUrl: this.images[0]['large_image_url'],
                        //         originalImageUrl: this.images[0]['original_image_url'],
                        //         currentType: this.images[0]['type']
                        //     })
                        // }

                        this.prepareThumbs()
                    }
                },

                created: function () {
                    // this.changeImage({
                    //     largeImageUrl: this.images[0]['large_image_url'],
                    //     originalImageUrl: this.images[0]['original_image_url'],
                    //     currentType: this.images[0]['type']
                    // });
                    eventBus.$on('configurable-variant-update-images-event', this.updateImages);

                    this.prepareThumbs();
                },

                methods: {
                    updateImages: function (galleryImages) {
                        this.images = galleryImages;
                    },

                    prepareThumbs: function () {
                        this.thumbs = [];

                        this.images.forEach(image => {
                            this.thumbs.push(image);
                        });
                    },

                    changeImage: function ({largeImageUrl, originalImageUrl, currentType}) {
                        this.currentLargeImageUrl = largeImageUrl;

                        this.currentOriginalImageUrl = originalImageUrl;

                        this.currentType = currentType;

                        this.$root.$emit('changeMagnifiedImage', {
                            smallImageUrl: this.currentOriginalImageUrl,
                            largeImageUrl: this.currentLargeImageUrl,
                            currentType: this.currentType
                        });

                        let productImage = $('.vc-small-product-image');
                        if (productImage && productImage[0]) {
                            productImage = productImage[0];

                            productImage.src = this.currentOriginalImageUrl;
                        }
                    },
                    showImage: function (index) {
                        console.log(index);
                        this.usingThumb = true;
                        this.$refs.galleryCarousel.goTo(this.images.length - index - 1,true);
                        // return;
                        // this.usingThumb = true;
                        // let productImage = $('.img-main-' + this.currentLargeImageIndex);
                        // let thumb = $('.thumb-' + this.currentLargeImageIndex);
                        // let pagination = $('.dot-' + this.currentLargeImageIndex);
                        // productImage.removeClass("is-active");
                        // thumb.removeClass("is-active");
                        // pagination.removeClass("is-active");
                        // this.currentLargeImageIndex = index;
                        // productImage = $('.img-main-' + index);
                        // thumb = $('.thumb-' + index);
                        // pagination = $('.dot-' + index);
                        // console.log(index);
                        // console.log(this.$refs.galleryCarousel);
                        // this.$refs.galleryCarousel.goTo(index,true);
                        // productImage.addClass("is-active");
                        // thumb.addClass("is-active");
                        // pagination.addClass("is-active");
                    },
                    scroll: function (navigateTo) {
                        let navigation = $(`#${this.galleryCarouselId} .VueCarousel-navigation .VueCarousel-navigation-${navigateTo}`);

                        if (navigation && (navigation = navigation[0])) {
                            navigation.click();
                        }
                    }, changeThumb: function (oldSlideIndex,newSlideIndex) {
                        console.log(oldSlideIndex,newSlideIndex);
                        // this.$refs.galleryCarousel.goTo(4);
                        $('[class*="thumb-"]').removeClass("is-active");
                        $('[class*="dot-"]').removeClass("is-active");
                        let navigateTo = (this.images.length - newSlideIndex - 1);
                        let thumb = $('.thumb-' + (navigateTo));
                        let pagination = $('.dot-' + (navigateTo));
                        thumb.addClass("is-active");
                        pagination.addClass("is-active");

                        // if(this.usingThumb){
                        //     this.usingThumb = false;
                        //     return;
                        // }
                        // // console.log("start nav: " + newSlideIndex);
                        // // console.log("calc nav: " + (this.images.length - newSlideIndex - 1));
                        // let navigateTo = (this.images.length - newSlideIndex - 1);
                        // // console.log("result: " + navigateTo);
                        // let thumb = $('.thumb-' + this.currentLargeImageIndex);
                        // let pagination = $('.dot-' + this.currentLargeImageIndex);
                        // thumb.removeClass("is-active");
                        // pagination.removeClass("is-active");
                        // this.currentLargeImageIndex = navigateTo;
                        // thumb = $('.thumb-' + navigateTo);
                        // pagination = $('.dot-' + navigateTo);
                        // thumb.addClass("is-active");
                        // pagination.addClass("is-active");
                    },
                }
            });
        })()
    </script>
@endpush