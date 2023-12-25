<v-products-carousel
    src="{{ $src }}"
    title="{{ $title }}"
    navigation-link="{{ $navigationLink ?? '' }}"
>
    <x-shop::shimmer.products.carousel
        :navigation-link="$navigationLink ?? false"
    ></x-shop::shimmer.products.carousel>
</v-products-carousel>

@pushOnce('scripts')
<script type="text/x-template" id="v-products-carousel-template">
    <div class="container mt-20 max-lg:px-[30px] max-sm:mt-[30px]" v-if="! isLoading && products.length">
        <div class="grid lg:grid-cols-4 md:grid-cols-6 bg-[#2b348f] rounded-lg gap-6">
            <div class="category-header hidden md:block text-white">
                <div class="category-title flex items-center text-2xl font-bold justify-center py-5">
                    TEST
                </div>
                <div class="category-image">
                    <img src="categoryImage" class="w-100 img-crisp" v-if="false">
                    <img src="/images/shop/carousel/free.png" class="w-100 img-crisp" v-else>
                </div>
                <div class="category-link" v-if="isCategory">
                    <a class="btn btn-outline-light"
                       :href="`/${navigationLink}`">
                        مشاهده همه
                    </a>
                </div>
            </div>
            <div
            ref="swiperContainer"
            class="col-span-full lg:col-span-3 md:col-span-5 flex gap-8 [&>*]:flex-[0] my-[20px] overflow-auto scroll-smooth scrollbar-hide max-sm:mt-[20px]"
        >
            <x-shop::products.card
                class="min-w-[201px]"
                v-for="product in products"
            >
            </x-shop::products.card>
        </div>
        </div>


        <a
            :href="navigationLink"
            class="secondary-button block w-max mt-[60px] mx-auto py-[11px] px-[43px] rounded-[18px] text-base text-center"
            v-if="navigationLink"
        >
            @lang('shop::app.components.products.carousel.view-all')
        </a>
    </div>

    <!-- Product Card Listing -->
    <template v-if="isLoading">
        <x-shop::shimmer.products.carousel :navigation-link="$navigationLink ?? false"></x-shop::shimmer.products.carousel>
    </template>
</script>

<script type="module">
    app.component("v-products-carousel", {
        template: "#v-products-carousel-template",

        props: ["src", "title", "navigationLink"],

        data() {
            return {
                isLoading: true,

                products: [],

                offset: 233,
            };
        },

        mounted() {
            this.getProducts();
            // this.play();
        },

        methods: {
            getProducts() {
                this.$axios
                    .get(this.src)
                    .then((response) => {
                        this.isLoading = false;

                        this.products = response.data.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            swipeLeft() {
                const container = this.$refs.swiperContainer;
                if (container === undefined) {
                    return;
                }
                container.scrollLeft -= this.offset;

                if (
                    Math.abs(container.scrollLeft) ===
                    container.scrollWidth - container.clientWidth
                ) {
                    container.scrollLeft = 0;
                }
            },

            swipeRight() {
                const container = this.$refs.swiperContainer;
                if (container === undefined) {
                    return;
                }
                container.scrollLeft += this.offset;
            },
            play() {
                let self = this;

                setInterval(() => {
                    this.swipeLeft();
                }, 5000);
            },
        },
    });
</script>
@endPushOnce
