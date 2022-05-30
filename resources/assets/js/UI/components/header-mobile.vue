<template>
    <div class="mobile-header">
        <div id="modal-blocker"></div>
        <div id="search-blocker"></div>
        <div class="row">
            <div class="col-3">
                <div v-if="hamburger" class="nav-container scrollable">
                    <div class="wrapper" v-if="this.rootCategories">
                        <div class="greeting drawer-section fw6">
                            <i class="fa fa-user-alt"></i>
                            <span>
                            <slot name="greetings"></slot>

                            <i
                                @click="closeDrawer()"
                                class="material-icons float-right text-dark"
                            >
                                cancel
                            </i>
                        </span>
                        </div>
                        <slot name="customer-navigation"></slot>
                        <ul
                            type="none"
                            class="velocity-content">
                            <li>
                                <a class="unset" :href="`${$root.baseUrl}/#`" target="_self">درباره ما</a>
                            </li>
                            <li>
                                <a class="unset" :href="`${$root.baseUrl}/#`" target="_self">گواهی نامه</a>
                            </li>
                        </ul>

                        <ul
                            type="none"
                            class="category-wrapper"
                            v-if="rootCategoriesCollection.length > 0"
                        >
                            <li
                                v-for="(
                                category, index
                            ) in rootCategoriesCollection"
                                :key="index"
                            >
                                <a
                                    class="unset"
                                    :href="`${$root.baseUrl}/${category.slug}`"
                                >
                                    <div class="category-logo">
                                        <img
                                            class="category-icon"
                                            v-if="category.category_icon_url"
                                            :src="category.category_icon_url"
                                            alt=""
                                            width="20"
                                            height="20"
                                        />
                                    </div>

                                    <span v-text="category.name"></span>
                                </a>

                                <i
                                    class="rango-arrow-right"
                                    @click="toggleSubcategories(index, $event)"
                                ></i>
                            </li>
                        </ul>

                        <ul type="none" class="meta-wrapper" v-if="hasExtraSlot()">
                            <slot name="extranavigation"></slot>
                        </ul>
                    </div>

                    <div class="wrapper" v-else-if="subCategory">
                        <div class="drawer-section">
                            <i
                                class="rango-arrow-left fs24 text-down-4"
                                @click="toggleSubcategories('root')"
                            ></i>

                            <h4 class="display-inbl" v-text="subCategory.name"></h4>

                            <i
                                class="material-icons float-right text-dark"
                                @click="closeDrawer()"
                            >
                                cancel
                            </i>
                        </div>

                        <ul type="none">
                            <li
                                :key="index"
                                v-for="(
                                nestedSubCategory, index
                            ) in subCategory.children"
                            >
                                <a
                                    class="unset"
                                    :href="`${$root.baseUrl}/${subCategory.slug}/${nestedSubCategory.slug}`"
                                >
                                    <div class="category-logo">
                                        <img
                                            class="category-icon"
                                            v-if="
                                            nestedSubCategory.category_icon_url
                                        "
                                            :src="
                                            nestedSubCategory.category_icon_url
                                        "
                                            alt=""
                                            width="25"
                                            height="25"
                                        />
                                    </div>

                                    <span v-text="nestedSubCategory.name"></span>
                                </a>

                                <ul
                                    type="none"
                                    class="nested-category"
                                    v-if="
                                    nestedSubCategory.children &&
                                    nestedSubCategory.children.length > 0
                                "
                                >
                                    <li
                                        :key="`index-${index}`"
                                        v-for="(
                                        thirdLevelCategory, index
                                    ) in nestedSubCategory.children"
                                    >
                                        <a
                                            class="unset"
                                            :href="`${$root.baseUrl}/${subCategory.slug}/${nestedSubCategory.slug}/${thirdLevelCategory.slug}`"
                                        >
                                            <div class="category-logo">
                                                <img
                                                    class="category-icon"
                                                    v-if="
                                                    thirdLevelCategory.category_icon_url
                                                "
                                                    :src="
                                                    thirdLevelCategory.category_icon_url
                                                "
                                                    alt=""
                                                    width="20"
                                                    height="20"
                                                />
                                            </div>

                                            <span
                                                v-text="thirdLevelCategory.name"
                                            ></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <div class="wrapper" v-else-if="languages">
                        <div class="drawer-section">
                            <i
                                class="rango-arrow-left fs24 text-down-4"
                                @click="toggleMetaInfo('languages')"
                            ></i>

                            <h4
                                class="display-inbl"
                                v-text="__('responsive.header.languages')"
                            ></h4>

                            <i
                                class="material-icons float-right text-dark"
                                @click="closeDrawer()"
                            >cancel</i
                            >
                        </div>

                        <ul type="none">
                            <li v-for="(locale, index) in allLocales" :key="index">
                                <a class="unset" :href="`?locale=${locale.code}`">
                                    <div class="category-logo">
                                        <img
                                            class="category-icon"
                                            :src="locale.image_url"
                                            alt=""
                                            width="20"
                                            height="20"
                                            v-if="locale.image_url"
                                        />
                                    </div>

                                    <span v-text="locale.name"></span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="wrapper" v-else-if="currencies">
                        <div class="drawer-section">
                            <i
                                class="rango-arrow-left fs24 text-down-4"
                                @click="toggleMetaInfo('currencies')"
                            ></i>

                            <h4
                                class="display-inbl"
                                v-text="__('shop.general.currencies')"
                            ></h4>

                            <i
                                class="material-icons float-right text-dark"
                                @click="closeDrawer()"
                            >cancel</i
                            >
                        </div>

                        <ul type="none">
                            <li
                                v-for="(currency, index) in allCurrencies"
                                :key="index"
                            >
                                <a
                                    class="unset"
                                    :href="`?currency=${currency.code}`"
                                >
                                    <span v-text="currency.code"></span>
                                </a>
                            </li>
                        </ul>
                    </div>


                </div>
                <div class="w-100 d-flex align-items-center">
                    <div class="hamburger-wrapper pl-2" @click="toggleHamburger">
                        <i class="fa fa- fa-bars"></i>
                    </div>
                    <div class="login-wrapper pr-2">
                        <slot name="login-link"></slot>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <slot name="logo"></slot>
            </div>
            <div class="right-vc-header  d-flex align-items-center justify-content-end  col-3">
                <slot name="top-header"></slot>

                <a class="unset px-1 cursor-pointer" @click="openSearchBar">
                    <i class="fa fa-search"></i>
                </a>

                <a :href="cartRoute" class="unset px-1">
                    <div class="icons-shop">
                        <i class="fa fa-shopping-cart"></i>
                    </div>

                    <div class="badge-wrapper">
                        <span class="badge" v-text="updatedCartItemsCount"></span>
                    </div>
                </a>
            </div>

            <div class="searchbar mobile" v-if="isSearchbar">
                <slot name="search-bar"></slot>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
    props: [
        'isCustomer',
        'heading',
        'headerContent',
        'categoryCount',
        'cartItemsCount',
        'cartRoute',
        'locale',
        'allLocales',
        'currency',
        'allCurrencies',
    ],

    data: function () {
        return {
            compareCount: 0,
            wishlistCount: 0,
            languages: false,
            hamburger: false,
            currencies: false,
            subCategory: null,
            isSearchbar: false,
            rootCategories: true,
            rootCategoriesCollection: this.$root.sharedRootCategories,
            updatedCartItemsCount: this.cartItemsCount,
        };
    },

    watch: {
        hamburger: function (value) {
            if (value) {
                document.body.classList.add('open-hamburger');
                var this_this = this;
                document.addEventListener('outSideNavClick', this.closeDrawer);
            } else {
                document.body.classList.remove('open-hamburger');
                document.removeEventListener('outSideNavClick', this.closeDrawer);
            }
        },

        '$root.headerItemsCount': function () {
            this.updateHeaderItemsCount();
        },

        '$root.miniCartKey': function () {
            this.getMiniCartDetails();
        },

        '$root.sharedRootCategories': function (categories) {
            this.formatCategories(categories);
        },
    },

    created: function () {
        this.getMiniCartDetails();

        this.updateHeaderItemsCount();

    },
    mounted() {

    },
    methods: {
        openSearchBar: function () {
            this.isSearchbar = !this.isSearchbar;
            let topbar = $('.top-bar-gif');
            if (this.isSearchbar) {
                topbar[0].style.zIndex = 11;
                document.addEventListener('outSideNavClick', this.closeSearchbar);
                document.body.classList.add('open-search');
            } else {
                topbar[0].style.zIndex = 0;
                document.body.classList.remove('open-search');
                document.removeEventListener('outSideNavClick', this.closeSearchbar);
            }
        },

        toggleHamburger: function () {
            this.hamburger = !this.hamburger;
        },
        closeSearchbar: function () {
            this.isSearchbar = false;
            let topbar = $('.top-bar-gif');
            topbar[0].style.zIndex = 0;
            document.body.classList.remove('open-search');
        },
        closeDrawer: function () {
            $('.nav-container').hide();

            this.hamburger = false;
            this.rootCategories = true;
        },
        hasExtraSlot() {
            console.log(this.$slots);
            return !!this.$slots.extranavigation;
        },
        toggleSubcategories: function (index, event) {
            if (index == 'root') {
                this.rootCategories = true;
                this.subCategory = false;
            } else {
                event.preventDefault();

                let categories = this.$root.sharedRootCategories;
                this.rootCategories = false;
                this.subCategory = categories[index];
            }
        },

        toggleMetaInfo: function (metaKey) {
            this.rootCategories = !this.rootCategories;

            this[metaKey] = !this[metaKey];
        },

        updateHeaderItemsCount: function () {
            if (this.isCustomer != 'true') {
                let comparedItems = this.getStorageValue('compared_product');

                if (comparedItems) {
                    this.compareCount = comparedItems.length;
                }
            } else {
                this.$http
                    .get(`${this.$root.baseUrl}/items-count`)
                    .then((response) => {
                        this.compareCount = response.data.compareProductsCount;
                        this.wishlistCount =
                            response.data.wishlistedProductsCount;
                    })
                    .catch((exception) => {
                        console.log(this.__('error.something_went_wrong'));
                    });
            }
        },

        getMiniCartDetails: function () {
            this.$http
                .get(`${this.$root.baseUrl}/mini-cart`)
                .then((response) => {
                    if (response.data.status) {
                        this.updatedCartItemsCount =
                            response.data.mini_cart.cart_items.length;
                    }
                })
                .catch((exception) => {
                    console.log(this.__('error.something_went_wrong'));
                });
        },

        formatCategories: function (categories) {
            let slicedCategories = categories;
            let categoryCount = this.categoryCount ? this.categoryCount : 9;

            if (slicedCategories && slicedCategories.length > categoryCount) {
                slicedCategories = categories.slice(0, categoryCount);
            }

            this.rootCategoriesCollection = slicedCategories;
        },
    },
};
</script>
