<template>
    <!-- categories list -->
    <div class="w-100 category-carousel"
         :class="`${addClass ? addClass : ''} ${localeDirection}` ">
        <slick-carousel
            v-bind="sliderSetting"
            v-if="slicedCategories && slicedCategories.length > 0">
            <div
                :id="`slide-${index}`"
                v-for="(category, index) in slicedCategories">
                <div class="category-item"  v-if="category.image">
                    <a
                        :href="`${$root.baseUrl}/${category.slug}`"
                        class="category"
                    :style="{'background-image': 'url('+ `${$root.baseUrl}/storage/${category.image}` + ')'}">
                        {{category.name}}
                    </a>
                </div>
                <div class="category-item" v-else>
                    <a
                       :href="`${$root.baseUrl}/${category.slug}`"
                       class="category"
                       style="">
                        {{category.name}}
                    </a>
                </div>

            </div>
        </slick-carousel>
    </div>

</template>

<script type="text/javascript">
    export default {
        props: [
            'id',
            'addClass',
            'parentSlug',
            'mainSidebar',
            'categoryCount',
            'localeDirection'
        ],

        data: function () {
            return {
                slicedCategories: [],
                sliderSetting: {}
            }
        },
        mounted: function () {
            this.sliderSetting = {
                "dots": false,
                "arrows": false,
                "autoplay": true,
                "speed": 1000,
                "rtl": (this.localeDirection === 'rtl'),
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
        },
        watch: {
            '$root.sharedRootCategories': function (categories) {
                this.formatCategories(categories);
            }
        },

        methods: {
            remainBar: function (id) {
                let sidebar = $(`#${id}`);
                if (sidebar && sidebar.length > 0) {
                    sidebar.show();

                    let actualId = id.replace('sidebar-level-', '');

                    let sidebarContainer = sidebar.closest(`.sub-category-${actualId}`)
                    if (sidebarContainer && sidebarContainer.length > 0) {
                        sidebarContainer.show();
                    }

                }
            },

            formatCategories: function (categories) {
                let slicedCategories = categories;
                let categoryCount = this.categoryCount ? this.categoryCount : 9;

                if (
                    slicedCategories
                    && slicedCategories.length > categoryCount
                ) {
                    slicedCategories = categories.slice(0, categoryCount);
                }

                if (this.parentSlug)
                    slicedCategories['parentSlug'] = this.parentSlug;

                this.slicedCategories = slicedCategories;
            },
        }
    }
</script>