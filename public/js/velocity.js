"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/velocity"],{

/***/ "./resources/assets/js/app.js":
/*!************************************!*\
  !*** ./resources/assets/js/app.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var accounting__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! accounting */ "./node_modules/accounting/accounting.js");
/* harmony import */ var accounting__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(accounting__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm.js");
/* harmony import */ var vee_validate__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vee-validate */ "./node_modules/vee-validate/dist/vee-validate.esm.js");
/* harmony import */ var vue_carousel__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-carousel */ "./node_modules/vue-carousel/dist/vue-carousel.min.js");
/* harmony import */ var vue_carousel__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(vue_carousel__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _Components_trans__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @Components/trans */ "./resources/assets/js/UI/components/trans.js");
/* harmony import */ var _Components_trans__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_Components_trans__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var vee_validate_dist_locale_ar__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! vee-validate/dist/locale/ar */ "./node_modules/vee-validate/dist/locale/ar.js");
/* harmony import */ var vee_validate_dist_locale_ar__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(vee_validate_dist_locale_ar__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var vee_validate_dist_locale_de__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! vee-validate/dist/locale/de */ "./node_modules/vee-validate/dist/locale/de.js");
/* harmony import */ var vee_validate_dist_locale_de__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(vee_validate_dist_locale_de__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _locale_fa__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./locale/fa */ "./resources/assets/js/locale/fa.js");
/* harmony import */ var vee_validate_dist_locale_fr__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! vee-validate/dist/locale/fr */ "./node_modules/vee-validate/dist/locale/fr.js");
/* harmony import */ var vee_validate_dist_locale_fr__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(vee_validate_dist_locale_fr__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var vee_validate_dist_locale_nl__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! vee-validate/dist/locale/nl */ "./node_modules/vee-validate/dist/locale/nl.js");
/* harmony import */ var vee_validate_dist_locale_nl__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(vee_validate_dist_locale_nl__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var vee_validate_dist_locale_tr__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! vee-validate/dist/locale/tr */ "./node_modules/vee-validate/dist/locale/tr.js");
/* harmony import */ var vee_validate_dist_locale_tr__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(vee_validate_dist_locale_tr__WEBPACK_IMPORTED_MODULE_9__);
/**
 * Main imports.
 */






/**
 * Lang imports.
 */







/**
 * Vue plugins.
 */
vue__WEBPACK_IMPORTED_MODULE_10__["default"].use((vue_carousel__WEBPACK_IMPORTED_MODULE_2___default()));
vue__WEBPACK_IMPORTED_MODULE_10__["default"].use(BootstrapSass);
var dictionary = {
  fa: {
    messages: {
      regex: function regex(field) {
        return n + ' نادرست است';
      }
    }
  }
};
vue__WEBPACK_IMPORTED_MODULE_10__["default"].use(vee_validate__WEBPACK_IMPORTED_MODULE_1__["default"], {
  dictionary: {
    ar: (vee_validate_dist_locale_ar__WEBPACK_IMPORTED_MODULE_4___default()),
    de: (vee_validate_dist_locale_de__WEBPACK_IMPORTED_MODULE_5___default()),
    fa: _locale_fa__WEBPACK_IMPORTED_MODULE_6__["default"],
    fr: (vee_validate_dist_locale_fr__WEBPACK_IMPORTED_MODULE_7___default()),
    nl: (vee_validate_dist_locale_nl__WEBPACK_IMPORTED_MODULE_8___default()),
    tr: (vee_validate_dist_locale_tr__WEBPACK_IMPORTED_MODULE_9___default())
  },
  events: 'input|change|blur'
});

/**
 * Filters.
 */
vue__WEBPACK_IMPORTED_MODULE_10__["default"].filter('currency', function (value, argument) {
  return accounting__WEBPACK_IMPORTED_MODULE_0___default().formatMoney(value, argument);
});

/**
 * Global components.
 */
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('vue-slider', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.t.bind(__webpack_require__, /*! vue-slider-component */ "./node_modules/vue-slider-component/dist/vue-slider-component.umd.min.js", 23));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('mini-cart-button', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/mini-cart-button */ "./resources/assets/js/UI/components/mini-cart-button.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('mini-cart', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/mini-cart */ "./resources/assets/js/UI/components/mini-cart.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('modal-component', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/modal */ "./resources/assets/js/UI/components/modal.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('add-to-cart', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/add-to-cart */ "./resources/assets/js/UI/components/add-to-cart.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('star-ratings', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/star-rating */ "./resources/assets/js/UI/components/star-rating.vue"));
});
// Vue.component('quantity-btn', () => import('@Components/quantity-btn'));
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('quantity-changer', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/quantity-changer */ "./resources/assets/js/UI/components/quantity-changer.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('proceed-to-checkout', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/proceed-to-checkout */ "./resources/assets/js/UI/components/proceed-to-checkout.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('compare-component-with-badge', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/header-compare-with-badge */ "./resources/assets/js/UI/components/header-compare-with-badge.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('searchbar-component', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/header-searchbar */ "./resources/assets/js/UI/components/header-searchbar.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('wishlist-component-with-badge', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/header-wishlist-with-badge */ "./resources/assets/js/UI/components/header-wishlist-with-badge.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('mobile-header', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/header-mobile */ "./resources/assets/js/UI/components/header-mobile.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('sidebar-header', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/header-sidebar */ "./resources/assets/js/UI/components/header-sidebar.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('right-side-header', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/header-right-side */ "./resources/assets/js/UI/components/header-right-side.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('sidebar-component', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/sidebar */ "./resources/assets/js/UI/components/sidebar.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('product-card', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/product-card */ "./resources/assets/js/UI/components/product-card.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('wishlist-component', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/wishlist */ "./resources/assets/js/UI/components/wishlist.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('carousel-component', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/carousel */ "./resources/assets/js/UI/components/carousel.vue"));
});
// Vue.component('slider-component', () => import('@Components/banners'));
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('child-sidebar', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/child-sidebar */ "./resources/assets/js/UI/components/child-sidebar.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('card-list-header', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/card-header */ "./resources/assets/js/UI/components/card-header.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('logo-component', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/image-logo */ "./resources/assets/js/UI/components/image-logo.vue"));
});
// Vue.component('magnify-image', () => import('@Components/image-magnifier'));
// Vue.component('image-search-component', () => import('@Components/image-search'));
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('compare-component', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/product-compare */ "./resources/assets/js/UI/components/product-compare.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('shimmer-component', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/shimmer-component */ "./resources/assets/js/UI/components/shimmer-component.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('responsive-sidebar', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/responsive-sidebar */ "./resources/assets/js/UI/components/responsive-sidebar.vue"));
});
// Vue.component('product-quick-view', () => import('@Components/product-quick-view'));
// Vue.component('product-quick-view-btn', () => import('@Components/product-quick-view-btn'));
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('recently-viewed', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/recently-viewed */ "./resources/assets/js/UI/components/recently-viewed.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('product-collections', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/product-collections */ "./resources/assets/js/UI/components/product-collections.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('hot-category', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/hot-category */ "./resources/assets/js/UI/components/hot-category.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('hot-categories', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/hot-categories */ "./resources/assets/js/UI/components/hot-categories.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('popular-category', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/popular-category */ "./resources/assets/js/UI/components/popular-category.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('popular-categories', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/popular-categories */ "./resources/assets/js/UI/components/popular-categories.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('velocity-overlay-loader', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/overlay-loader */ "./resources/assets/js/UI/components/overlay-loader.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('slick-carousel', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.t.bind(__webpack_require__, /*! vue-slick-carousel */ "./node_modules/vue-slick-carousel/dist/vue-slick-carousel.umd.js", 23));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('sms-timer', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/sms-timer */ "./resources/assets/js/UI/components/sms-timer.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('category-carousel', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/category-carousel */ "./resources/assets/js/UI/components/category-carousel.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('teacher-collections', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/teacher-collections */ "./resources/assets/js/UI/components/teacher-collections.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('qoutes', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/qoutes */ "./resources/assets/js/UI/components/qoutes.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('contracts', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/contracts */ "./resources/assets/js/UI/components/contracts.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('product-collections-slot', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.bind(__webpack_require__, /*! @Components/product-collections-slot */ "./resources/assets/js/UI/components/product-collections-slot.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('p-datepicker', function () {
  return Promise.all(/*! import() */[__webpack_require__.e("js/components"), __webpack_require__.e("node_modules_moment_locale_sync_recursive_")]).then(__webpack_require__.bind(__webpack_require__, /*! @Components/datepicker */ "./resources/assets/js/UI/components/datepicker.vue"));
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('vnode-injector', {
  functional: true,
  props: ['nodes'],
  render: function render(h, _ref) {
    var props = _ref.props;
    return props.nodes;
  }
});
vue__WEBPACK_IMPORTED_MODULE_10__["default"].component('go-top', function () {
  return __webpack_require__.e(/*! import() */ "js/components").then(__webpack_require__.t.bind(__webpack_require__, /*! @inotom/vue-go-top */ "./node_modules/@inotom/vue-go-top/dist/vue-go-top.umd.js", 23));
});

/**
 * Start from here.
 */
$(function () {
  /**
   * Define a mixin object.
   */
  vue__WEBPACK_IMPORTED_MODULE_10__["default"].mixin((_Components_trans__WEBPACK_IMPORTED_MODULE_3___default()));
  vue__WEBPACK_IMPORTED_MODULE_10__["default"].mixin({
    data: function data() {
      return {
        imageObserver: null,
        navContainer: false,
        headerItemsCount: 0,
        sharedRootCategories: [],
        responsiveSidebarTemplate: '',
        responsiveSidebarKey: Math.random(),
        baseUrl: getBaseUrl()
      };
    },
    methods: {
      redirect: function redirect(route) {
        route ? window.location.href = route : '';
      },
      debounceToggleSidebar: function debounceToggleSidebar(id, _ref2, type) {
        var target = _ref2.target;
        this.toggleSidebar(id, target, type);
      },
      toggleSidebar: function toggleSidebar(id, _ref3, type) {
        var target = _ref3.target;
        if (Array.from(target.classList)[0] === 'main-category' || Array.from(target.parentElement.classList)[0] === 'main-category') {
          var sidebar = $("#sidebar-level-".concat(id));
          if (sidebar && sidebar.length > 0) {
            if (type === 'mouseover') {
              this.show(sidebar);
            } else if (type === 'mouseout') {
              this.hide(sidebar);
            }
          }
        } else if (Array.from(target.classList)[0] === 'category' || Array.from(target.classList)[0] === 'category-icon' || Array.from(target.classList)[0] === 'category-title' || Array.from(target.classList)[0] === 'category-content' || Array.from(target.classList)[0] === 'rango-arrow-right') {
          var parentItem = target.closest('li');
          if (target.id || parentItem.id.match('category-')) {
            var subCategories = $("#".concat(target.id ? target.id : parentItem.id, " .sub-categories"));
            if (subCategories && subCategories.length > 0) {
              var subCategories1 = Array.from(subCategories)[0];
              subCategories1 = $(subCategories1);
              if (type === 'mouseover') {
                this.show(subCategories1);
                var sidebarChild = subCategories1.find('.sidebar');
                this.show(sidebarChild);
              } else if (type === 'mouseout') {
                this.hide(subCategories1);
              }
            } else {
              if (type === 'mouseout') {
                var _sidebar = $("#".concat(id));
                _sidebar.hide();
              }
            }
          }
        }
      },
      show: function show(element) {
        element.show();
        element.mouseleave(function (_ref4) {
          var target = _ref4.target;
          $(target.closest('.sidebar')).hide();
        });
      },
      hide: function hide(element) {
        element.hide();
      },
      toggleButtonDisability: function toggleButtonDisability(_ref5) {
        var event = _ref5.event,
          actionType = _ref5.actionType;
        var button = event.target.querySelector('button[type=submit]');
        button ? button.disabled = actionType : '';
      },
      onSubmit: function onSubmit(event) {
        var _this = this;
        this.toggleButtonDisability({
          event: event,
          actionType: true
        });
        if (typeof tinyMCE !== 'undefined') tinyMCE.triggerSave();
        this.$validator.validateAll().then(function (result) {
          if (result) {
            event.target.submit();
          } else {
            _this.toggleButtonDisability({
              event: event,
              actionType: false
            });
            eventBus.$emit('onFormError');
          }
        });
      },
      isMobile: isMobile,
      loadDynamicScript: function (_loadDynamicScript) {
        function loadDynamicScript(_x, _x2) {
          return _loadDynamicScript.apply(this, arguments);
        }
        loadDynamicScript.toString = function () {
          return _loadDynamicScript.toString();
        };
        return loadDynamicScript;
      }(function (src, onScriptLoaded) {
        loadDynamicScript(src, onScriptLoaded);
      }),
      getDynamicHTML: function getDynamicHTML(input) {
        var _staticRenderFns, output;
        var _Vue$compile = vue__WEBPACK_IMPORTED_MODULE_10__["default"].compile(input),
          render = _Vue$compile.render,
          staticRenderFns = _Vue$compile.staticRenderFns;
        if (this.$options.staticRenderFns.length > 0) {
          _staticRenderFns = this.$options.staticRenderFns;
        } else {
          _staticRenderFns = this.$options.staticRenderFns = staticRenderFns;
        }
        try {
          output = render.call(this, this.$createElement);
        } catch (exception) {
          console.log(this.__('error.something_went_wrong'));
        }
        this.$options.staticRenderFns = _staticRenderFns;
        return output;
      },
      getStorageValue: function getStorageValue(key) {
        var value = window.localStorage.getItem(key);
        if (value) {
          value = JSON.parse(value);
        }
        return value;
      },
      setStorageValue: function setStorageValue(key, value) {
        window.localStorage.setItem(key, JSON.stringify(value));
        return true;
      }
    }
  });
  window.app = new vue__WEBPACK_IMPORTED_MODULE_10__["default"]({
    el: '#app',
    data: function data() {
      return {
        loading: false,
        modalIds: {},
        miniCartKey: 0,
        quickView: false,
        productDetails: []
      };
    },
    mounted: function mounted() {
      this.$validator.localize(document.documentElement.lang);
      this.addServerErrors();
      this.loadCategories();
      this.addIntersectionObserver();
      console.log("Vue Loading");
      $(document).trigger('vue-loaded');
    },
    methods: {
      onSubmit: function onSubmit(event) {
        var _this2 = this;
        this.toggleButtonDisability({
          event: event,
          actionType: true
        });
        if (typeof tinyMCE !== 'undefined') tinyMCE.triggerSave();
        this.$validator.validateAll().then(function (result) {
          if (result) {
            event.target.submit();
          } else {
            _this2.toggleButtonDisability({
              event: event,
              actionType: false
            });
            eventBus.$emit('onFormError');
          }
        });
      },
      toggleButtonDisable: function toggleButtonDisable(value) {
        var buttons = document.getElementsByTagName('button');
        for (var i = 0; i < buttons.length; i++) {
          buttons[i].disabled = value;
        }
      },
      addServerErrors: function addServerErrors() {
        var _this3 = this;
        var scope = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
        var _loop = function _loop() {
          var inputNames = [];
          key.split('.').forEach(function (chunk, index) {
            if (index) {
              inputNames.push('[' + chunk + ']');
            } else {
              inputNames.push(chunk);
            }
          });
          var inputName = inputNames.join('');
          var field = _this3.$validator.fields.find({
            name: inputName,
            scope: scope
          });
          if (field) {
            _this3.$validator.errors.add({
              id: field.id,
              field: inputName,
              msg: serverErrors[key][0],
              scope: scope
            });
          }
        };
        for (var key in serverErrors) {
          _loop();
        }
      },
      addFlashMessages: function addFlashMessages() {
        if (window.flashMessages.alertMessage) window.alert(window.flashMessages.alertMessage);
      },
      showModal: function showModal(id) {
        this.$set(this.modalIds, id, true);
      },
      loadCategories: function loadCategories() {
        var _this4 = this;
        this.$http.get("".concat(this.baseUrl, "/categories")).then(function (response) {
          _this4.sharedRootCategories = response.data.categories;
          $("<style type='text/css'> .sub-categories{ min-height:".concat(response.data.categories.length * 30, "px;} </style>")).appendTo('head');
        })["catch"](function (error) {
          console.error("Failed to load categories:", error);
        });
      },
      addIntersectionObserver: function addIntersectionObserver() {
        this.imageObserver = new IntersectionObserver(function (entries, imgObserver) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              var lazyImage = entry.target;
              lazyImage.src = lazyImage.dataset.src;
            }
          });
        });
      },
      showLoader: function showLoader() {
        this.loading = true;
      },
      hideLoader: function hideLoader() {
        this.loading = false;
      },
      togglePopup: function togglePopup() {
        var accountModal = $('#account-modal');
        var modal = $('#cart-modal-content');
        if (modal) modal.addClass('hide');
        accountModal.toggleClass('hide');
      }
    }
  });
});

/***/ }),

/***/ "./resources/assets/js/locale/fa.js":
/*!******************************************!*\
  !*** ./resources/assets/js/locale/fa.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils */ "./resources/assets/js/locale/utils.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : String(i); }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

var localizeSize = function localizeSize(size) {
  var map = {
    Byte: 'بايت',
    KB: 'كيلوبايت',
    GB: 'گیگابايت',
    PB: 'پتابايت'
  };
  return (0,_utils__WEBPACK_IMPORTED_MODULE_0__.formatFileSize)(size).replace(/(Byte|KB|GB|PB)/, function (m) {
    return map[m];
  });
};
var messages = {
  _default: function _default(field) {
    return "\u0645\u0642\u062F\u0627\u0631 ".concat(field, " \u0645\u0639\u062A\u0628\u0631 \u0646\u06CC\u0633\u062A");
  },
  after: function after(field, _ref) {
    var _ref2 = _slicedToArray(_ref, 1),
      target = _ref2[0];
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u0628\u0639\u062F \u0627\u0632 \u062A\u0627\u0631\u06CC\u062E ").concat(target, " \u0628\u0627\u0634\u062F");
  },
  alpha: function alpha(field) {
    return "".concat(field, " \u0641\u0642\u0637 \u0645\u06CC \u062A\u0648\u0627\u0646\u062F \u0627\u0632 \u062D\u0631\u0648\u0641 \u062A\u0634\u06A9\u06CC\u0644 \u0634\u0648\u062F");
  },
  alpha_dash: function alpha_dash(field) {
    return "".concat(field, " \u0641\u0642\u0637 \u0645\u06CC \u062A\u0648\u0627\u0646\u062F \u0627\u0632 \u062D\u0631\u0648\u0641\u060C \u0627\u0639\u062F\u0627\u062F\u060C \u062E\u0637 \u0641\u0627\u0635\u0644\u0647 \u0648 \u0632\u06CC\u0631\u062E\u0637 \u062A\u0634\u06A9\u06CC\u0644 \u0634\u0648\u062F");
  },
  alpha_num: function alpha_num(field) {
    return "".concat(field, " \u0641\u0642\u0637 \u0645\u06CC\u062A\u0648\u0627\u0646\u062F \u0627\u0632 \u062D\u0631\u0648\u0641 \u0648 \u0627\u0639\u062F\u0627\u062F \u062A\u0634\u06A9\u06CC\u0644 \u0634\u0648\u062F");
  },
  alpha_spaces: function alpha_spaces(field) {
    return "".concat(field, " \u0641\u0642\u0637 \u0645\u06CC \u062A\u0648\u0627\u0646\u062F \u0627\u0632 \u062D\u0631\u0648\u0641 \u0648 \u0641\u0627\u0635\u0644\u0647 \u062A\u0634\u06A9\u06CC\u0644 \u0634\u0648\u062F");
  },
  before: function before(field, _ref3) {
    var _ref4 = _slicedToArray(_ref3, 1),
      target = _ref4[0];
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u0642\u0628\u0644 \u0627\u0632 \u062A\u0627\u0631\u06CC\u062E ").concat(target, " \u0628\u0627\u0634\u062F");
  },
  between: function between(field, _ref5) {
    var _ref6 = _slicedToArray(_ref5, 2),
      min = _ref6[0],
      max = _ref6[1];
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u0628\u06CC\u0646 ").concat(min, " \u0648 ").concat(max, " \u06A9\u0627\u0631\u06A9\u062A\u0631 \u0628\u0627\u0634\u062F");
  },
  confirmed: function confirmed(field) {
    return "".concat(field, " \u0628\u0627 \u062A\u0627\u06CC\u06CC\u062F\u06CC\u0647 \u0627\u0634 \u0645\u0637\u0627\u0628\u0642\u062A \u0646\u062F\u0627\u0631\u062F");
  },
  credit_card: function credit_card(field) {
    return "".concat(field, " \u063A\u06CC\u0631 \u0645\u0639\u062A\u0628\u0631 \u0627\u0633\u062A");
  },
  date_between: function date_between(field, _ref7) {
    var _ref8 = _slicedToArray(_ref7, 2),
      min = _ref8[0],
      max = _ref8[1];
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u0628\u06CC\u0646 \u062A\u0627\u0631\u06CC\u062E ").concat(min, " and ").concat(max, " \u0628\u0627\u0634\u062F");
  },
  date_format: function date_format(field, _ref9) {
    var _ref10 = _slicedToArray(_ref9, 1),
      format = _ref10[0];
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u062F\u0631 \u0642\u0627\u0644\u0628 ").concat(format, " \u0628\u0627\u0634\u062F");
  },
  decimal: function decimal(field) {
    var _ref11 = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [],
      _ref12 = _slicedToArray(_ref11, 1),
      _ref12$ = _ref12[0],
      decimals = _ref12$ === void 0 ? '*' : _ref12$;
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u06CC\u06A9 \u0645\u0642\u062F\u0627\u0631 \u0639\u062F\u062F\u06CC ").concat(decimals === '*' ? '' : ' با حداکثر ' + decimals + ' اعشار', " \u0628\u0627\u0634\u062F");
  },
  digits: function digits(field, _ref13) {
    var _ref14 = _slicedToArray(_ref13, 1),
      length = _ref14[0];
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u06CC\u06A9 \u0645\u0642\u062F\u0627\u0631 \u0639\u062F\u062F\u06CC \u0648 \u062F\u0642\u06CC\u0642\u0627\u064B ").concat(length, " \u0631\u0642\u0645 \u0628\u0627\u0634\u062F");
  },
  dimensions: function dimensions(field, _ref15) {
    var _ref16 = _slicedToArray(_ref15, 2),
      width = _ref16[0],
      height = _ref16[1];
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u062F\u0631 \u0627\u0646\u062F\u0627\u0632\u0647 ").concat(width, " \u067E\u06CC\u06A9\u0633\u0644 \u0639\u0631\u0636 \u0648 ").concat(height, " \u067E\u06CC\u06A9\u0633\u0644 \u0627\u0631\u062A\u0641\u0627\u0639 \u0628\u0627\u0634\u062F");
  },
  email: function email(field) {
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u06CC\u06A9 \u067E\u0633\u062A \u0627\u0644\u06A9\u062A\u0631\u0648\u0646\u06CC\u06A9 \u0645\u0639\u062A\u0628\u0631 \u0628\u0627\u0634\u062F");
  },
  excluded: function excluded(field) {
    return "".concat(field, "\u0628\u0627\u06CC\u062F \u06CC\u06A9 \u0645\u0642\u062F\u0627\u0631 \u0645\u0639\u062A\u0628\u0631 \u0628\u0627\u0634\u062F");
  },
  ext: function ext(field) {
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u06CC\u06A9 \u0641\u0627\u06CC\u0644 \u0645\u0639\u062A\u0628\u0631 \u0628\u0627\u0634\u062F");
  },
  image: function image(field) {
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u06CC\u06A9 \u062A\u0635\u0648\u06CC\u0631 \u0628\u0627\u0634\u062F");
  },
  included: function included(field) {
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u06CC\u06A9 \u0645\u0642\u062F\u0627\u0631 \u0645\u0639\u062A\u0628\u0631 \u0628\u0627\u0634\u062F");
  },
  ip: function ip(field) {
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u06CC\u06A9 \u0622\u062F\u0631\u0633 \u0622\u06CC \u067E\u06CC \u0645\u0639\u062A\u0628\u0631 \u0628\u0627\u0634\u062F");
  },
  max: function max(field, _ref17) {
    var _ref18 = _slicedToArray(_ref17, 1),
      length = _ref18[0];
    return "".concat(field, " \u0646\u0628\u0627\u06CC\u062F \u0628\u06CC\u0634\u062A\u0631 \u0627\u0632 ").concat(length, " \u06A9\u0627\u0631\u06A9\u062A\u0631 \u0628\u0627\u0634\u062F");
  },
  max_value: function max_value(field, _ref19) {
    var _ref20 = _slicedToArray(_ref19, 1),
      max = _ref20[0];
    return "\u0645\u0642\u062F\u0627\u0631 ".concat(field, " \u0628\u0627\u06CC\u062F ").concat(max, " \u06CC\u0627 \u06A9\u0645\u062A\u0631 \u0628\u0627\u0634\u062F");
  },
  mimes: function mimes(field) {
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u0627\u0632 \u0646\u0648\u0639 \u0645\u0639\u062A\u0628\u0631 \u0628\u0627\u0634\u062F");
  },
  min: function min(field, _ref21) {
    var _ref22 = _slicedToArray(_ref21, 1),
      length = _ref22[0];
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u062D\u062F\u0627\u0642\u0644 ").concat(length, " \u06A9\u0627\u0631\u06A9\u062A\u0631 \u0628\u0627\u0634\u062F");
  },
  min_value: function min_value(field, _ref23) {
    var _ref24 = _slicedToArray(_ref23, 1),
      min = _ref24[0];
    return "\u0645\u0642\u062F\u0627\u0631 ".concat(field, " \u0628\u0627\u06CC\u062F ").concat(min, " \u06CC\u0627 \u0628\u06CC\u0634\u062A\u0631 \u0628\u0627\u0634\u062F");
  },
  numeric: function numeric(field) {
    return "".concat(field, " \u0641\u0642\u0637 \u0645\u06CC \u062A\u0648\u0627\u0646\u062F \u0639\u062F\u062F\u06CC \u0628\u0627\u0634\u062F");
  },
  regex: function regex(field) {
    return "".concat(field, " \u0646\u0627\u062F\u0631\u0633\u062A \u0627\u0633\u062A");
  },
  required: function required(field) {
    return "".concat(field, " \u0627\u0644\u0632\u0627\u0645\u06CC \u0627\u0633\u062A");
  },
  size: function size(field, _ref25) {
    var _ref26 = _slicedToArray(_ref25, 1),
      _size = _ref26[0];
    return "\u062D\u062C\u0645 ".concat(field, " \u06A9\u0645\u062A\u0631 \u0627\u0632 ").concat(localizeSize(_size), " \u0628\u0627\u0634\u062F");
  },
  url: function url(field) {
    return "".concat(field, " \u0628\u0627\u06CC\u062F \u06CC\u06A9 \u062A\u0627\u0631\u0646\u0645\u0627\u06CC \u0645\u0639\u062A\u0628\u0631 \u0628\u0627\u0634\u062F");
  }
};
var locale = {
  name: 'fa',
  messages: messages,
  attributes: {}
};
if ((0,_utils__WEBPACK_IMPORTED_MODULE_0__.isDefinedGlobally)()) {
  VeeValidate.Validator.localize(_defineProperty({}, locale.name, locale));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (locale);

/***/ }),

/***/ "./resources/assets/js/locale/utils.js":
/*!*********************************************!*\
  !*** ./resources/assets/js/locale/utils.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   formatFileSize: () => (/* binding */ formatFileSize),
/* harmony export */   isDefinedGlobally: () => (/* binding */ isDefinedGlobally)
/* harmony export */ });
/**
 * Formates file size.
 *
 * @param {Number|String} size
 */
var formatFileSize = function formatFileSize(size) {
  var units = ['Byte', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  var threshold = 1024;
  size = Number(size) * threshold;
  var i = size === 0 ? 0 : Math.floor(Math.log(size) / Math.log(threshold));
  return "".concat((size / Math.pow(threshold, i)).toFixed(2) * 1, " ").concat(units[i]);
};

/**
 * Checks if vee-validate is defined globally.
 */
var isDefinedGlobally = function isDefinedGlobally() {
  return typeof VeeValidate !== 'undefined';
};

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["js/components"], () => (__webpack_exec__("./resources/assets/js/app.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);