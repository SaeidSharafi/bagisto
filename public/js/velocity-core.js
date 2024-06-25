"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/velocity-core"],{

/***/ "./resources/assets/js/app-core.js":
/*!*****************************************!*\
  !*** ./resources/assets/js/app-core.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _app_helpers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./app-helpers */ "./resources/assets/js/app-helpers.js");
/**
 * Main imports.
 */



/**
 * Helper functions.
 */


/**
 * Vue prototype.
 */
vue__WEBPACK_IMPORTED_MODULE_2__["default"].prototype.$http = (axios__WEBPACK_IMPORTED_MODULE_0___default());

/**
 * Window assignation.
 */
window.Vue = vue__WEBPACK_IMPORTED_MODULE_2__["default"];
window.eventBus = new vue__WEBPACK_IMPORTED_MODULE_2__["default"]();
window.axios = (axios__WEBPACK_IMPORTED_MODULE_0___default());

// TODO once every package is migrated to laravel-mix 6, this can be removed safely (jquery will be injected when needed)
window.jQuery = window.$ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
__webpack_require__(/*! ./dropdown.js */ "./resources/assets/js/dropdown.js");
window.BootstrapSass = __webpack_require__(/*! bootstrap-sass */ "./node_modules/bootstrap-sass/assets/javascripts/bootstrap.js");
window.lazySize = __webpack_require__(/*! lazysizes */ "./node_modules/lazysizes/lazysizes.js");
window.getBaseUrl = _app_helpers__WEBPACK_IMPORTED_MODULE_1__.getBaseUrl;
window.isMobile = _app_helpers__WEBPACK_IMPORTED_MODULE_1__.isMobile;
window.loadDynamicScript = _app_helpers__WEBPACK_IMPORTED_MODULE_1__.loadDynamicScript;
window.showAlert = _app_helpers__WEBPACK_IMPORTED_MODULE_1__.showAlert;

/**
 * Dynamic loading for mobile.
 */
$(function () {
  /**
   * Base url.
   */
  var baseUrl = (0,_app_helpers__WEBPACK_IMPORTED_MODULE_1__.getBaseUrl)();

  /**
   * Velocity JS path. Just make sure if you are renaming
   * file then update this path also for mobile.
   */
  var velocityJSPath = 'js/velocity.js';
  // loadDynamicScript(`${baseUrl}/${velocityJSPath}`, () => {});
  // if (
  //     isMobile() &&
  //     removeTrailingSlash(baseUrl) === removeTrailingSlash(window.location.href)
  // ) {
  //     /**
  //      * Event for mobile to check the user interaction for the homepage. In mobile,
  //      * if your viewport is having dynamic content then, feel free to override this.
  //      * Else it is recommended to have some, static content in the viewport as the
  //      * first impression to reduce LCP.
  //      */
  //     document.addEventListener(
  //         'touchstart',
  //         function dynamicScript() {
  //             window.scrollTo(0, 0);
  //
  //             document.body.style.overflow = 'hidden';
  //
  //             loadDynamicScript(`${baseUrl}/${velocityJSPath}`, () => {
  //                 window.scrollTo(0, 0);
  //
  //                 document.body.style.overflow = '';
  //
  //                 this.removeEventListener('touchstart', dynamicScript);
  //             });
  //         },
  //         false
  //     );
  // } else {
  //     /**
  //      * Else leave it default as previous.
  //      */
  //     loadDynamicScript(`${baseUrl}/${velocityJSPath}`, () => {});
  // }
});

/***/ }),

/***/ "./resources/assets/js/app-helpers.js":
/*!********************************************!*\
  !*** ./resources/assets/js/app-helpers.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   getBaseUrl: () => (/* binding */ getBaseUrl),
/* harmony export */   isMobile: () => (/* binding */ isMobile),
/* harmony export */   loadDynamicScript: () => (/* binding */ loadDynamicScript),
/* harmony export */   removeTrailingSlash: () => (/* binding */ removeTrailingSlash),
/* harmony export */   showAlert: () => (/* binding */ showAlert)
/* harmony export */ });
function getBaseUrl() {
  return document.querySelector('meta[name="base-url"]').content;
}
function isMobile() {
  return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i | /mobi/i.test(navigator.userAgent);
}
function loadDynamicScript(src, onScriptLoaded) {
  var dynamicScript = document.createElement('script');
  dynamicScript.setAttribute('src', src);
  document.body.appendChild(dynamicScript);
  dynamicScript.addEventListener('load', onScriptLoaded, false);
}
function showAlert(messageType, messageLabel, message) {
  if (messageType && message !== '') {
    var alertId = Math.floor(Math.random() * 1000);
    var html = "<div class=\"alert ".concat(messageType, " alert-dismissible\" id=\"").concat(alertId, "\">\n            <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>\n                <strong>").concat(messageLabel ? messageLabel + '!' : '', " </strong> ").concat(message, ".\n        </div>");
    $('#alert-container').append(html).ready(function () {
      window.setTimeout(function () {
        $("#alert-container #".concat(alertId)).remove();
      }, 5000);
    });
  }
}
function removeTrailingSlash(site) {
  return site.replace(/\/$/, '');
}

/***/ }),

/***/ "./resources/assets/js/dropdown.js":
/*!*****************************************!*\
  !*** ./resources/assets/js/dropdown.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);

$(function () {
  var event = new Event('outSideNavClick');
  $(document).click(function (e) {
    var target = e.target;
    if (!$(target).parents('.vpd-wrapper').length) {
      if (!$(target).parents('.dropdown-open').length || $(target).is('li') || $(target).is('a')) {
        $('.dropdown-list').hide();
        $('.dropdown-toggle').removeClass('active');
      }
    }
    if ($(target).is("#modal-blocker") || $(target).is("#search-blocker")) {
      document.dispatchEvent(event);
    }
  });
  $('body').delegate('.dropdown-toggle', 'click', function (e) {
    e.stopImmediatePropagation();
    console.log('.dropdown-toggle');
    toggleDropdown(e);
  });
  function toggleDropdown(e) {
    var currentElement = $(e.currentTarget);
    $('.dropdown-list').hide();
    if (currentElement.hasClass('active')) {
      currentElement.removeClass('active');
    } else {
      currentElement.addClass('active');
      currentElement.parent().find('.dropdown-list').fadeIn(100);
      currentElement.parent().addClass('dropdown-open');
      autoDropupDropdown();
    }
  }
  function autoDropupDropdown() {
    var dropdown = $(".dropdown-open");
    if (!dropdown.find('.dropdown-list').hasClass('top-left') && !dropdown.find('.dropdown-list').hasClass('top-right') && dropdown.length) {
      dropdown = dropdown.find('.dropdown-list');
      var height = dropdown.height() + 50;
      var topOffset = dropdown.offset().top - 70;
      var bottomOffset = $(window).height() - topOffset - dropdown.height();
      if (bottomOffset > topOffset || height < bottomOffset) {
        dropdown.removeClass("bottom");
        if (dropdown.hasClass('top-right')) {
          dropdown.removeClass('top-right');
          dropdown.addClass('bottom-right');
        } else if (dropdown.hasClass('top-left')) {
          dropdown.removeClass('top-left');
          dropdown.addClass('bottom-left');
        }
      } else {
        if (dropdown.hasClass('bottom-right')) {
          dropdown.removeClass('bottom-right');
          dropdown.addClass('top-right');
        } else if (dropdown.hasClass('bottom-left')) {
          dropdown.removeClass('bottom-left');
          dropdown.addClass('top-left');
        }
      }
    }
  }
  $('div').scroll(function () {
    autoDropupDropdown();
  });
});

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["js/components"], () => (__webpack_exec__("./resources/assets/js/app-core.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);