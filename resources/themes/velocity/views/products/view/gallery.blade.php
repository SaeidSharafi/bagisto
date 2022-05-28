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
        <div class="prodcut-images-main">
            <div class="main-image"
                 :class="[`img-main-${index}` ,index == 0 ? 'is-active' : '']"
                 v-for="(image, index) in images" v-bind:key="index">
                <video v-if="image.type == 'video'" width="100%" preload="none" controls>
                    <source :src="image.large_image_url" type="video/mp4">
                </video>
                <img loading="lazy" v-else
                     :src="image.large_image_url">
            </div>
        </div>
        <div class="carousel-pagination d-md-none" v-if="thumbs.length > 1">
            <ul>
                <li v-for="(thumb, index) in thumbs" v-bind:key="index" @click="showImage(index)"
                    :class="`carousel-dot ${'dot-'+index} ${index === 0 ? 'is-active' : ''}`" >

                </li>
            </ul>
        </div>
        <ul class="thumb-list w-100 row ltr d-none d-md-block">
            <li class="arrow left px-1" @click="scroll('prev')" v-if="thumbs.length > 4">
                <i class="fas fa-chevron-left fs24"></i>
            </li>

            <carousel-component
                slides-per-page="4"
                :id="galleryCarouselId"
                pagination-enabled="true"
                navigation-enabled="hide"
                add-class="product-gallery"
                :slides-count="thumbs.length">

                <slide :slot="`slide-${index}`" v-for="(thumb, index) in thumbs" v-bind:key="index">
                    <li
                        @click="showImage(index)"
                        :class="`thumb-frame ${index + 1 == 4 ? '' : 'mr5'} ${thumb.large_image_url == currentLargeImageUrl ? 'active' : ''} ${'thumb-'+index} ${index == 0 ? 'is-active' : ''}`"
                    >

                        <div v-if="thumb.type == 'video'"
                             class="bg-video"
                        >
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div v-else
                             class="bg-image"
                             :style="`background-image: url(${thumb.small_image_url})`">
                        </div>
                    </li>
                </slide>
            </carousel-component>

            <li class="arrow right px-1" @click="scroll('next')" v-if="thumbs.length > 4">
                <i class="fas fa-chevron-right fs24"></i>
            </li>
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
                        counter: {
                            up: 0,
                            down: 0,
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
                        let productImage = $('.img-main-' + this.currentLargeImageIndex);
                        let thumb = $('.thumb-' + this.currentLargeImageIndex);
                        let pagination = $('.dot-' + this.currentLargeImageIndex);
                        productImage.removeClass("is-active");
                        thumb.removeClass("is-active");
                        pagination.removeClass("is-active");
                        this.currentLargeImageIndex = index;
                        productImage = $('.img-main-' + index);
                        thumb = $('.thumb-' + index);
                        pagination = $('.dot-' + index);
                        productImage.addClass("is-active");
                        thumb.addClass("is-active");
                        pagination.addClass("is-active");
                    },
                    scroll: function (navigateTo) {
                        let navigation = $(`#${this.galleryCarouselId} .VueCarousel-navigation .VueCarousel-navigation-${navigateTo}`);

                        if (navigation && (navigation = navigation[0])) {
                            navigation.click();
                        }
                    },
                }
            });
        })()
    </script>
@endpush