/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/slider"],{

/***/ "./Resources/assets/js/slider.js":
/*!***************************************!*\
  !*** ./Resources/assets/js/slider.js ***!
  \***************************************/
/***/ (() => {

eval("var slideshowDuration = 4000;\n\nfunction slideshowSwitch(slideshow, index, auto) {\n  if (slideshow.data('wait')) return;\n  var slides = slideshow.find('.slide');\n  var pages = slideshow.find('.pagination');\n  var activeSlide = slides.filter('.is-active');\n  var activeSlideImage = activeSlide.find('.image-container');\n  var newSlide = slides.eq(index);\n  var newSlideImage = newSlide.find('.image-container');\n  var newSlideContent = newSlide.find('.slide-content');\n  var newSlideElements = newSlide.find('.caption > *');\n  if (newSlide.is(activeSlide)) return;\n  newSlide.addClass('is-new');\n  var timeout = slideshow.data('timeout');\n  clearTimeout(timeout);\n  slideshow.data('wait', true);\n  var transition = slideshow.attr('data-transition');\n\n  if (transition == 'fade') {\n    newSlide.css({\n      display: 'block',\n      zIndex: 2\n    });\n    newSlideImage.css({\n      opacity: 0\n    });\n    TweenMax.to(newSlideImage, 1, {\n      alpha: 1,\n      onComplete: function onComplete() {\n        newSlide.addClass('is-active').removeClass('is-new');\n        activeSlide.removeClass('is-active');\n        newSlide.css({\n          display: '',\n          zIndex: ''\n        });\n        newSlideImage.css({\n          opacity: ''\n        });\n        slideshow.find('.pagination').trigger('check');\n        slideshow.data('wait', false);\n\n        if (auto) {\n          console.log(\"Auto\");\n          timeout = setTimeout(function () {\n            console.log(\"Next2\");\n            slideshowNext(slideshow, false, true);\n          }, slideshowDuration);\n          slideshow.data('timeout', timeout);\n        }\n      }\n    });\n  } else {\n    if (newSlide.index() > activeSlide.index()) {\n      var newSlideRight = 0;\n      var newSlideLeft = 'auto';\n      var newSlideImageRight = -slideshow.width() / 8;\n      var newSlideImageLeft = 'auto';\n      var newSlideImageToRight = 0;\n      var newSlideImageToLeft = 'auto';\n      var newSlideContentLeft = 'auto';\n      var newSlideContentRight = 0;\n      var activeSlideImageLeft = -slideshow.width() / 4;\n    } else {\n      var newSlideRight = '';\n      var newSlideLeft = 0;\n      var newSlideImageRight = 'auto';\n      var newSlideImageLeft = -slideshow.width() / 8;\n      var newSlideImageToRight = '';\n      var newSlideImageToLeft = 0;\n      var newSlideContentLeft = 0;\n      var newSlideContentRight = 'auto';\n      var activeSlideImageLeft = slideshow.width() / 4;\n    }\n\n    newSlide.css({\n      display: 'block',\n      width: 0,\n      right: newSlideRight,\n      left: newSlideLeft,\n      zIndex: 2\n    });\n    newSlideImage.css({\n      width: slideshow.width(),\n      right: newSlideImageRight,\n      left: newSlideImageLeft\n    });\n    newSlideContent.css({\n      width: slideshow.width(),\n      left: newSlideContentLeft,\n      right: newSlideContentRight\n    });\n    activeSlideImage.css({\n      left: 0\n    });\n    TweenMax.set(newSlideElements, {\n      y: 20,\n      force3D: true\n    });\n    TweenMax.to(activeSlideImage, 1, {\n      left: activeSlideImageLeft,\n      ease: Power3.easeInOut\n    });\n    TweenMax.to(newSlide, 1, {\n      width: slideshow.width(),\n      ease: Power3.easeInOut\n    });\n    TweenMax.to(newSlideImage, 1, {\n      right: newSlideImageToRight,\n      left: newSlideImageToLeft,\n      ease: Power3.easeInOut\n    });\n    TweenMax.staggerFromTo(newSlideElements, 0.8, {\n      alpha: 0,\n      y: 60\n    }, {\n      alpha: 1,\n      y: 0,\n      ease: Power3.easeOut,\n      force3D: true,\n      delay: 0.6\n    }, 0.1, function () {\n      newSlide.addClass('is-active').removeClass('is-new');\n      activeSlide.removeClass('is-active');\n      newSlide.css({\n        display: '',\n        width: '',\n        left: '',\n        zIndex: ''\n      });\n      newSlideImage.css({\n        width: '',\n        right: '',\n        left: ''\n      });\n      newSlideContent.css({\n        width: '',\n        left: ''\n      });\n      newSlideElements.css({\n        opacity: '',\n        transform: ''\n      });\n      activeSlideImage.css({\n        left: ''\n      });\n      slideshow.find('.pagination').trigger('check');\n      slideshow.data('wait', false);\n\n      if (auto) {\n        timeout = setTimeout(function () {\n          slideshowNext(slideshow, false, true);\n        }, slideshowDuration);\n        slideshow.data('timeout', timeout);\n      }\n    });\n  }\n}\n\nfunction slideshowNext(slideshow, previous, auto) {\n  var slides = slideshow.find('.slide');\n  var activeSlide = slides.filter('.is-active');\n  var newSlide = null;\n\n  if (previous) {\n    newSlide = activeSlide.prev('.slide');\n\n    if (newSlide.length === 0) {\n      newSlide = slides.last();\n    }\n  } else {\n    newSlide = activeSlide.next('.slide');\n    if (newSlide.length == 0) newSlide = slides.filter('.slide').first();\n  }\n\n  slideshowSwitch(slideshow, newSlide.index(), auto);\n}\n\nfunction homeSlideshowParallax() {\n  var slideshow = $('.slideshow-wrapper .slideshow');\n  var scrollTop = $(window).scrollTop();\n  if (scrollTop > windowHeight) return;\n  var inner = slideshow.find('.slideshow-inner');\n  var newHeight = windowHeight - scrollTop / 2;\n  var newTop = scrollTop * 0.8;\n  inner.css({\n    transform: 'translateY(' + newTop + 'px)',\n    height: newHeight\n  });\n}\n\n$(document).on('vue-loaded', function () {\n  $('.slide').addClass('is-loaded');\n  var slideshow = $('.slideshow-wrapper .slideshow');\n  $('.slideshow .arrows .arrow').on('click', function () {\n    slideshowNext($(this).closest('.slideshow'), $(this).hasClass('prev'));\n  });\n  $('.slideshow .pagination .item').on('click', function () {\n    slideshowSwitch($(this).closest('.slideshow'), $(this).index());\n  });\n  $('.slideshow .pagination').on('check', function () {\n    var slideshow = $(this).closest('.slideshow');\n    var pages = $(this).find('.item');\n    var index = slideshow.find('.slides .is-active').index();\n    pages.removeClass('is-active');\n    pages.eq(index).addClass('is-active');\n  });\n  /* Lazyloading\n  $('.slideshow').each(function(){\n    var slideshow=$(this);\n    var images=slideshow.find('.image').not('.is-loaded');\n    images.on('loaded',function(){\n      var image=$(this);\n      var slide=image.closest('.slide');\n      slide.addClass('is-loaded');\n    });\n  */\n\n  var timeout = setTimeout(function () {\n    console.log(\"Next\");\n    slideshowNext(slideshow, false, true);\n  }, slideshowDuration);\n  slideshow.data('timeout', timeout);\n});\n\nif ($('.main-content .slideshow').length > 1) {\n  $(window).on('scroll', homeSlideshowParallax);\n}\n\ndocument.addEventListener(\"DOMContentLoaded\", function () {\n  $('.slide').addClass('is-loaded');\n}); // import Splide from '@splidejs/splide';\n// // import Swiper from 'tiny-swiper'\n//\n// document.addEventListener(\"DOMContentLoaded\", () => {\n//     console.log(\"document loaded\");\n//     var splide = new Splide( '.splide', {\n//         type  : 'loop',\n//         speed: 600,\n//         rewind: true,\n//         lazyLoad: 'nearby',\n//         autoplay: true,\n//     } );\n//     splide.mount();\n// });\n//\n// $(document).on('vue-loaded', function () {\n//     console.log(\"Vue Loadewd\");\n//\n//     // const swiper = new Swiper(\".swiper-container\");\n// });//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9SZXNvdXJjZXMvYXNzZXRzL2pzL3NsaWRlci5qcz9jZDVlIl0sIm5hbWVzIjpbInNsaWRlc2hvd0R1cmF0aW9uIiwic2xpZGVzaG93U3dpdGNoIiwic2xpZGVzaG93IiwiaW5kZXgiLCJhdXRvIiwiZGF0YSIsInNsaWRlcyIsImZpbmQiLCJwYWdlcyIsImFjdGl2ZVNsaWRlIiwiZmlsdGVyIiwiYWN0aXZlU2xpZGVJbWFnZSIsIm5ld1NsaWRlIiwiZXEiLCJuZXdTbGlkZUltYWdlIiwibmV3U2xpZGVDb250ZW50IiwibmV3U2xpZGVFbGVtZW50cyIsImlzIiwiYWRkQ2xhc3MiLCJ0aW1lb3V0IiwiY2xlYXJUaW1lb3V0IiwidHJhbnNpdGlvbiIsImF0dHIiLCJjc3MiLCJkaXNwbGF5IiwiekluZGV4Iiwib3BhY2l0eSIsIlR3ZWVuTWF4IiwidG8iLCJhbHBoYSIsIm9uQ29tcGxldGUiLCJyZW1vdmVDbGFzcyIsInRyaWdnZXIiLCJjb25zb2xlIiwibG9nIiwic2V0VGltZW91dCIsInNsaWRlc2hvd05leHQiLCJuZXdTbGlkZVJpZ2h0IiwibmV3U2xpZGVMZWZ0IiwibmV3U2xpZGVJbWFnZVJpZ2h0Iiwid2lkdGgiLCJuZXdTbGlkZUltYWdlTGVmdCIsIm5ld1NsaWRlSW1hZ2VUb1JpZ2h0IiwibmV3U2xpZGVJbWFnZVRvTGVmdCIsIm5ld1NsaWRlQ29udGVudExlZnQiLCJuZXdTbGlkZUNvbnRlbnRSaWdodCIsImFjdGl2ZVNsaWRlSW1hZ2VMZWZ0IiwicmlnaHQiLCJsZWZ0Iiwic2V0IiwieSIsImZvcmNlM0QiLCJlYXNlIiwiUG93ZXIzIiwiZWFzZUluT3V0Iiwic3RhZ2dlckZyb21UbyIsImVhc2VPdXQiLCJkZWxheSIsInRyYW5zZm9ybSIsInByZXZpb3VzIiwicHJldiIsImxlbmd0aCIsImxhc3QiLCJuZXh0IiwiZmlyc3QiLCJob21lU2xpZGVzaG93UGFyYWxsYXgiLCIkIiwic2Nyb2xsVG9wIiwid2luZG93Iiwid2luZG93SGVpZ2h0IiwiaW5uZXIiLCJuZXdIZWlnaHQiLCJuZXdUb3AiLCJoZWlnaHQiLCJkb2N1bWVudCIsIm9uIiwiY2xvc2VzdCIsImhhc0NsYXNzIiwiYWRkRXZlbnRMaXN0ZW5lciJdLCJtYXBwaW5ncyI6IkFBQUEsSUFBSUEsaUJBQWlCLEdBQUcsSUFBeEI7O0FBR0EsU0FBU0MsZUFBVCxDQUF5QkMsU0FBekIsRUFBb0NDLEtBQXBDLEVBQTJDQyxJQUEzQyxFQUFpRDtBQUM3QyxNQUFJRixTQUFTLENBQUNHLElBQVYsQ0FBZSxNQUFmLENBQUosRUFBNEI7QUFFNUIsTUFBSUMsTUFBTSxHQUFHSixTQUFTLENBQUNLLElBQVYsQ0FBZSxRQUFmLENBQWI7QUFDQSxNQUFJQyxLQUFLLEdBQUdOLFNBQVMsQ0FBQ0ssSUFBVixDQUFlLGFBQWYsQ0FBWjtBQUNBLE1BQUlFLFdBQVcsR0FBR0gsTUFBTSxDQUFDSSxNQUFQLENBQWMsWUFBZCxDQUFsQjtBQUNBLE1BQUlDLGdCQUFnQixHQUFHRixXQUFXLENBQUNGLElBQVosQ0FBaUIsa0JBQWpCLENBQXZCO0FBQ0EsTUFBSUssUUFBUSxHQUFHTixNQUFNLENBQUNPLEVBQVAsQ0FBVVYsS0FBVixDQUFmO0FBQ0EsTUFBSVcsYUFBYSxHQUFHRixRQUFRLENBQUNMLElBQVQsQ0FBYyxrQkFBZCxDQUFwQjtBQUNBLE1BQUlRLGVBQWUsR0FBR0gsUUFBUSxDQUFDTCxJQUFULENBQWMsZ0JBQWQsQ0FBdEI7QUFDQSxNQUFJUyxnQkFBZ0IsR0FBR0osUUFBUSxDQUFDTCxJQUFULENBQWMsY0FBZCxDQUF2QjtBQUNBLE1BQUlLLFFBQVEsQ0FBQ0ssRUFBVCxDQUFZUixXQUFaLENBQUosRUFBOEI7QUFFOUJHLEVBQUFBLFFBQVEsQ0FBQ00sUUFBVCxDQUFrQixRQUFsQjtBQUNBLE1BQUlDLE9BQU8sR0FBR2pCLFNBQVMsQ0FBQ0csSUFBVixDQUFlLFNBQWYsQ0FBZDtBQUNBZSxFQUFBQSxZQUFZLENBQUNELE9BQUQsQ0FBWjtBQUNBakIsRUFBQUEsU0FBUyxDQUFDRyxJQUFW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QUFRQTtBQUNKO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFSSxNQUFJQyxPQUFPLEdBQUdnQixVQUFVLENBQUMsWUFBWTtBQUNqQ0YsSUFBQUEsT0FBTyxDQUFDQyxHQUFSLENBQVksTUFBWjtBQUNBRSxJQUFBQSxhQUFhLENBQUNsQyxTQUFELEVBQVksS0FBWixFQUFtQixJQUFuQixDQUFiO0FBQ0gsR0FIdUIsRUFHckJGLGlCQUhxQixDQUF4QjtBQUtBRSxFQUFBQSxTQUFTLENBQUNHLElBQVYsQ0FBZSxTQUFmLEVBQTBCYyxPQUExQjtBQUNILENBcENEOztBQXNDQSxJQUFJK0MsQ0FBQyxDQUFDLDBCQUFELENBQUQsQ0FBOEJMLE1BQTlCLEdBQXVDLENBQTNDLEVBQThDO0FBQzFDSyxFQUFBQSxDQUFDLENBQUNFLE1BQUQsQ0FBRCxDQUFVTyxFQUFWLENBQWEsUUFBYixFQUF1QlYscUJBQXZCO0FBQ0g7O0FBQ0RTLFFBQVEsQ0FBQ0ksZ0JBQVQsQ0FBMEIsa0JBQTFCLEVBQThDLFlBQU07QUFDaERaLEVBQUFBLENBQUMsQ0FBQyxRQUFELENBQUQsQ0FBWWhELFFBQVosQ0FBcUIsV0FBckI7QUFDSCxDQUZELEUsQ0FHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBIiwic291cmNlc0NvbnRlbnQiOlsidmFyIHNsaWRlc2hvd0R1cmF0aW9uID0gNDAwMDtcblxuXG5mdW5jdGlvbiBzbGlkZXNob3dTd2l0Y2goc2xpZGVzaG93LCBpbmRleCwgYXV0bykge1xuICAgIGlmIChzbGlkZXNob3cuZGF0YSgnd2FpdCcpKSByZXR1cm47XG5cbiAgICB2YXIgc2xpZGVzID0gc2xpZGVzaG93LmZpbmQoJy5zbGlkZScpO1xuICAgIHZhciBwYWdlcyA9IHNsaWRlc2hvdy5maW5kKCcucGFnaW5hdGlvbicpO1xuICAgIHZhciBhY3RpdmVTbGlkZSA9IHNsaWRlcy5maWx0ZXIoJy5pcy1hY3RpdmUnKTtcbiAgICB2YXIgYWN0aXZlU2xpZGVJbWFnZSA9IGFjdGl2ZVNsaWRlLmZpbmQoJy5pbWFnZS1jb250YWluZXInKTtcbiAgICB2YXIgbmV3U2xpZGUgPSBzbGlkZXMuZXEoaW5kZXgpO1xuICAgIHZhciBuZXdTbGlkZUltYWdlID0gbmV3U2xpZGUuZmluZCgnLmltYWdlLWNvbnRhaW5lcicpO1xuICAgIHZhciBuZXdTbGlkZUNvbnRlbnQgPSBuZXdTbGlkZS5maW5kKCcuc2xpZGUtY29udGVudCcpO1xuICAgIHZhciBuZXdTbGlkZUVsZW1lbnRzID0gbmV3U2xpZGUuZmluZCgnLmNhcHRpb24gPiAqJyk7XG4gICAgaWYgKG5ld1NsaWRlLmlzKGFjdGl2ZVNsaWRlKSkgcmV0dXJuO1xuXG4gICAgbmV3U2xpZGUuYWRkQ2xhc3MoJ2lzLW5ldycpO1xuICAgIHZhciB0aW1lb3V0ID0gc2xpZGVzaG93LmRhdGEoJ3RpbWVvdXQnKTtcbiAgICBjbGVhclRpbWVvdXQodGltZW91dCk7XG4gICAgc2xpZGVzaG93LmRhdGEoJ3dhaXQnLCB0cnVlKTtcbiAgICB2YXIgdHJhbnNpdGlvbiA9IHNsaWRlc2hvdy5hdHRyKCdkYXRhLXRyYW5zaXRpb24nKTtcbiAgICBpZiAodHJhbnNpdGlvbiA9PSAnZmFkZScpIHtcbiAgICAgICAgbmV3U2xpZGUuY3NzKHtcbiAgICAgICAgICAgIGRpc3BsYXk6ICdibG9jaycsXG4gICAgICAgICAgICB6SW5kZXg6IDJcbiAgICAgICAgfSk7XG4gICAgICAgIG5ld1NsaWRlSW1hZ2UuY3NzKHtcbiAgICAgICAgICAgIG9wYWNpdHk6IDBcbiAgICAgICAgfSk7XG5cbiAgICAgICAgVHdlZW5NYXgudG8obmV3U2xpZGVJbWFnZSwgMSwge1xuICAgICAgICAgICAgYWxwaGE6IDEsXG4gICAgICAgICAgICBvbkNvbXBsZXRlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgbmV3U2xpZGUuYWRkQ2xhc3MoJ2lzLWFjdGl2ZScpLnJlbW92ZUNsYXNzKCdpcy1uZXcnKTtcbiAgICAgICAgICAgICAgICBhY3RpdmVTbGlkZS5yZW1vdmVDbGFzcygnaXMtYWN0aXZlJyk7XG4gICAgICAgICAgICAgICAgbmV3U2xpZGUuY3NzKHtkaXNwbGF5OiAnJywgekluZGV4OiAnJ30pO1xuICAgICAgICAgICAgICAgIG5ld1NsaWRlSW1hZ2UuY3NzKHtvcGFjaXR5OiAnJ30pO1xuICAgICAgICAgICAgICAgIHNsaWRlc2hvdy5maW5kKCcucGFnaW5hdGlvbicpLnRyaWdnZXIoJ2NoZWNrJyk7XG4gICAgICAgICAgICAgICAgc2xpZGVzaG93LmRhdGEoJ3dhaXQnLCBmYWxzZSk7XG4gICAgICAgICAgICAgICAgaWYgKGF1dG8pIHtcbiAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coXCJBdXRvXCIpO1xuICAgICAgICAgICAgICAgICAgICB0aW1lb3V0ID0gc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhcIk5leHQyXCIpO1xuICAgICAgICAgICAgICAgICAgICAgICAgc2xpZGVzaG93TmV4dChzbGlkZXNob3csIGZhbHNlLCB0cnVlKTtcbiAgICAgICAgICAgICAgICAgICAgfSwgc2xpZGVzaG93RHVyYXRpb24pO1xuICAgICAgICAgICAgICAgICAgICBzbGlkZXNob3cuZGF0YSgndGltZW91dCcsIHRpbWVvdXQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfSBlbHNlIHtcbiAgICAgICAgaWYgKG5ld1NsaWRlLmluZGV4KCkgPiBhY3RpdmVTbGlkZS5pbmRleCgpKSB7XG4gICAgICAgICAgICB2YXIgbmV3U2xpZGVSaWdodCA9IDA7XG4gICAgICAgICAgICB2YXIgbmV3U2xpZGVMZWZ0ID0gJ2F1dG8nO1xuICAgICAgICAgICAgdmFyIG5ld1NsaWRlSW1hZ2VSaWdodCA9IC1zbGlkZXNob3cud2lkdGgoKSAvIDg7XG4gICAgICAgICAgICB2YXIgbmV3U2xpZGVJbWFnZUxlZnQgPSAnYXV0byc7XG4gICAgICAgICAgICB2YXIgbmV3U2xpZGVJbWFnZVRvUmlnaHQgPSAwO1xuICAgICAgICAgICAgdmFyIG5ld1NsaWRlSW1hZ2VUb0xlZnQgPSAnYXV0byc7XG4gICAgICAgICAgICB2YXIgbmV3U2xpZGVDb250ZW50TGVmdCA9ICdhdXRvJztcbiAgICAgICAgICAgIHZhciBuZXdTbGlkZUNvbnRlbnRSaWdodCA9IDA7XG4gICAgICAgICAgICB2YXIgYWN0aXZlU2xpZGVJbWFnZUxlZnQgPSAtc2xpZGVzaG93LndpZHRoKCkgLyA0O1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdmFyIG5ld1NsaWRlUmlnaHQgPSAnJztcbiAgICAgICAgICAgIHZhciBuZXdTbGlkZUxlZnQgPSAwO1xuICAgICAgICAgICAgdmFyIG5ld1NsaWRlSW1hZ2VSaWdodCA9ICdhdXRvJztcbiAgICAgICAgICAgIHZhciBuZXdTbGlkZUltYWdlTGVmdCA9IC1zbGlkZXNob3cud2lkdGgoKSAvIDg7XG4gICAgICAgICAgICB2YXIgbmV3U2xpZGVJbWFnZVRvUmlnaHQgPSAnJztcbiAgICAgICAgICAgIHZhciBuZXdTbGlkZUltYWdlVG9MZWZ0ID0gMDtcbiAgICAgICAgICAgIHZhciBuZXdTbGlkZUNvbnRlbnRMZWZ0ID0gMDtcbiAgICAgICAgICAgIHZhciBuZXdTbGlkZUNvbnRlbnRSaWdodCA9ICdhdXRvJztcbiAgICAgICAgICAgIHZhciBhY3RpdmVTbGlkZUltYWdlTGVmdCA9IHNsaWRlc2hvdy53aWR0aCgpIC8gNDtcbiAgICAgICAgfVxuXG4gICAgICAgIG5ld1NsaWRlLmNzcyh7XG4gICAgICAgICAgICBkaXNwbGF5OiAnYmxvY2snLFxuICAgICAgICAgICAgd2lkdGg6IDAsXG4gICAgICAgICAgICByaWdodDogbmV3U2xpZGVSaWdodCxcbiAgICAgICAgICAgIGxlZnQ6IG5ld1NsaWRlTGVmdFxuICAgICAgICAgICAgLCB6SW5kZXg6IDJcbiAgICAgICAgfSk7XG5cbiAgICAgICAgbmV3U2xpZGVJbWFnZS5jc3Moe1xuICAgICAgICAgICAgd2lkdGg6IHNsaWRlc2hvdy53aWR0aCgpLFxuICAgICAgICAgICAgcmlnaHQ6IG5ld1NsaWRlSW1hZ2VSaWdodCxcbiAgICAgICAgICAgIGxlZnQ6IG5ld1NsaWRlSW1hZ2VMZWZ0XG4gICAgICAgIH0pO1xuXG4gICAgICAgIG5ld1NsaWRlQ29udGVudC5jc3Moe1xuICAgICAgICAgICAgd2lkdGg6IHNsaWRlc2hvdy53aWR0aCgpLFxuICAgICAgICAgICAgbGVmdDogbmV3U2xpZGVDb250ZW50TGVmdCxcbiAgICAgICAgICAgIHJpZ2h0OiBuZXdTbGlkZUNvbnRlbnRSaWdodFxuICAgICAgICB9KTtcblxuICAgICAgICBhY3RpdmVTbGlkZUltYWdlLmNzcyh7XG4gICAgICAgICAgICBsZWZ0OiAwXG4gICAgICAgIH0pO1xuXG4gICAgICAgIFR3ZWVuTWF4LnNldChuZXdTbGlkZUVsZW1lbnRzLCB7eTogMjAsIGZvcmNlM0Q6IHRydWV9KTtcbiAgICAgICAgVHdlZW5NYXgudG8oYWN0aXZlU2xpZGVJbWFnZSwgMSwge1xuICAgICAgICAgICAgbGVmdDogYWN0aXZlU2xpZGVJbWFnZUxlZnQsXG4gICAgICAgICAgICBlYXNlOiBQb3dlcjMuZWFzZUluT3V0XG4gICAgICAgIH0pO1xuXG4gICAgICAgIFR3ZWVuTWF4LnRvKG5ld1NsaWRlLCAxLCB7XG4gICAgICAgICAgICB3aWR0aDogc2xpZGVzaG93LndpZHRoKCksXG4gICAgICAgICAgICBlYXNlOiBQb3dlcjMuZWFzZUluT3V0XG4gICAgICAgIH0pO1xuXG4gICAgICAgIFR3ZWVuTWF4LnRvKG5ld1NsaWRlSW1hZ2UsIDEsIHtcbiAgICAgICAgICAgIHJpZ2h0OiBuZXdTbGlkZUltYWdlVG9SaWdodCxcbiAgICAgICAgICAgIGxlZnQ6IG5ld1NsaWRlSW1hZ2VUb0xlZnQsXG4gICAgICAgICAgICBlYXNlOiBQb3dlcjMuZWFzZUluT3V0XG4gICAgICAgIH0pO1xuXG4gICAgICAgIFR3ZWVuTWF4LnN0YWdnZXJGcm9tVG8obmV3U2xpZGVFbGVtZW50cywgMC44LCB7YWxwaGE6IDAsIHk6IDYwfSwge2FscGhhOiAxLCB5OiAwLCBlYXNlOiBQb3dlcjMuZWFzZU91dCwgZm9yY2UzRDogdHJ1ZSwgZGVsYXk6IDAuNn0sIDAuMSwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgbmV3U2xpZGUuYWRkQ2xhc3MoJ2lzLWFjdGl2ZScpLnJlbW92ZUNsYXNzKCdpcy1uZXcnKTtcbiAgICAgICAgICAgIGFjdGl2ZVNsaWRlLnJlbW92ZUNsYXNzKCdpcy1hY3RpdmUnKTtcbiAgICAgICAgICAgIG5ld1NsaWRlLmNzcyh7XG4gICAgICAgICAgICAgICAgZGlzcGxheTogJycsXG4gICAgICAgICAgICAgICAgd2lkdGg6ICcnLFxuICAgICAgICAgICAgICAgIGxlZnQ6ICcnLFxuICAgICAgICAgICAgICAgIHpJbmRleDogJydcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBuZXdTbGlkZUltYWdlLmNzcyh7XG4gICAgICAgICAgICAgICAgd2lkdGg6ICcnLFxuICAgICAgICAgICAgICAgIHJpZ2h0OiAnJyxcbiAgICAgICAgICAgICAgICBsZWZ0OiAnJ1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIG5ld1NsaWRlQ29udGVudC5jc3Moe1xuICAgICAgICAgICAgICAgIHdpZHRoOiAnJyxcbiAgICAgICAgICAgICAgICBsZWZ0OiAnJ1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIG5ld1NsaWRlRWxlbWVudHMuY3NzKHtcbiAgICAgICAgICAgICAgICBvcGFjaXR5OiAnJyxcbiAgICAgICAgICAgICAgICB0cmFuc2Zvcm06ICcnXG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgYWN0aXZlU2xpZGVJbWFnZS5jc3Moe1xuICAgICAgICAgICAgICAgIGxlZnQ6ICcnXG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgc2xpZGVzaG93LmZpbmQoJy5wYWdpbmF0aW9uJykudHJpZ2dlcignY2hlY2snKTtcbiAgICAgICAgICAgIHNsaWRlc2hvdy5kYXRhKCd3YWl0JywgZmFsc2UpO1xuICAgICAgICAgICAgaWYgKGF1dG8pIHtcbiAgICAgICAgICAgICAgICB0aW1lb3V0ID0gc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHNsaWRlc2hvd05leHQoc2xpZGVzaG93LCBmYWxzZSwgdHJ1ZSk7XG4gICAgICAgICAgICAgICAgfSwgc2xpZGVzaG93RHVyYXRpb24pO1xuICAgICAgICAgICAgICAgIHNsaWRlc2hvdy5kYXRhKCd0aW1lb3V0JywgdGltZW91dCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH1cbn1cblxuZnVuY3Rpb24gc2xpZGVzaG93TmV4dChzbGlkZXNob3csIHByZXZpb3VzLCBhdXRvKSB7XG4gICAgdmFyIHNsaWRlcyA9IHNsaWRlc2hvdy5maW5kKCcuc2xpZGUnKTtcbiAgICB2YXIgYWN0aXZlU2xpZGUgPSBzbGlkZXMuZmlsdGVyKCcuaXMtYWN0aXZlJyk7XG4gICAgdmFyIG5ld1NsaWRlID0gbnVsbDtcbiAgICBpZiAocHJldmlvdXMpIHtcbiAgICAgICAgbmV3U2xpZGUgPSBhY3RpdmVTbGlkZS5wcmV2KCcuc2xpZGUnKTtcbiAgICAgICAgaWYgKG5ld1NsaWRlLmxlbmd0aCA9PT0gMCkge1xuICAgICAgICAgICAgbmV3U2xpZGUgPSBzbGlkZXMubGFzdCgpO1xuICAgICAgICB9XG4gICAgfSBlbHNlIHtcbiAgICAgICAgbmV3U2xpZGUgPSBhY3RpdmVTbGlkZS5uZXh0KCcuc2xpZGUnKTtcbiAgICAgICAgaWYgKG5ld1NsaWRlLmxlbmd0aCA9PSAwKVxuICAgICAgICAgICAgbmV3U2xpZGUgPSBzbGlkZXMuZmlsdGVyKCcuc2xpZGUnKS5maXJzdCgpO1xuICAgIH1cblxuICAgIHNsaWRlc2hvd1N3aXRjaChzbGlkZXNob3csIG5ld1NsaWRlLmluZGV4KCksIGF1dG8pO1xufVxuXG5mdW5jdGlvbiBob21lU2xpZGVzaG93UGFyYWxsYXgoKSB7XG4gICAgdmFyIHNsaWRlc2hvdyA9ICQoJy5zbGlkZXNob3ctd3JhcHBlciAuc2xpZGVzaG93Jyk7XG4gICAgdmFyIHNjcm9sbFRvcCA9ICQod2luZG93KS5zY3JvbGxUb3AoKTtcbiAgICBpZiAoc2Nyb2xsVG9wID4gd2luZG93SGVpZ2h0KSByZXR1cm47XG4gICAgdmFyIGlubmVyID0gc2xpZGVzaG93LmZpbmQoJy5zbGlkZXNob3ctaW5uZXInKTtcbiAgICB2YXIgbmV3SGVpZ2h0ID0gd2luZG93SGVpZ2h0IC0gKHNjcm9sbFRvcCAvIDIpO1xuICAgIHZhciBuZXdUb3AgPSBzY3JvbGxUb3AgKiAwLjg7XG5cbiAgICBpbm5lci5jc3Moe1xuICAgICAgICB0cmFuc2Zvcm06ICd0cmFuc2xhdGVZKCcgKyBuZXdUb3AgKyAncHgpJywgaGVpZ2h0OiBuZXdIZWlnaHRcbiAgICB9KTtcbn1cblxuJChkb2N1bWVudCkub24oJ3Z1ZS1sb2FkZWQnLCBmdW5jdGlvbiAoKSB7XG4gICAgJCgnLnNsaWRlJykuYWRkQ2xhc3MoJ2lzLWxvYWRlZCcpO1xuICAgIHZhciBzbGlkZXNob3cgPSAkKCcuc2xpZGVzaG93LXdyYXBwZXIgLnNsaWRlc2hvdycpO1xuICAgICQoJy5zbGlkZXNob3cgLmFycm93cyAuYXJyb3cnKS5vbignY2xpY2snLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHNsaWRlc2hvd05leHQoJCh0aGlzKS5jbG9zZXN0KCcuc2xpZGVzaG93JyksICQodGhpcykuaGFzQ2xhc3MoJ3ByZXYnKSk7XG4gICAgfSk7XG5cbiAgICAkKCcuc2xpZGVzaG93IC5wYWdpbmF0aW9uIC5pdGVtJykub24oJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xuICAgICAgICBzbGlkZXNob3dTd2l0Y2goJCh0aGlzKS5jbG9zZXN0KCcuc2xpZGVzaG93JyksICQodGhpcykuaW5kZXgoKSk7XG4gICAgfSk7XG5cbiAgICAkKCcuc2xpZGVzaG93IC5wYWdpbmF0aW9uJykub24oJ2NoZWNrJywgZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgc2xpZGVzaG93ID0gJCh0aGlzKS5jbG9zZXN0KCcuc2xpZGVzaG93Jyk7XG4gICAgICAgIHZhciBwYWdlcyA9ICQodGhpcykuZmluZCgnLml0ZW0nKTtcbiAgICAgICAgdmFyIGluZGV4ID0gc2xpZGVzaG93LmZpbmQoJy5zbGlkZXMgLmlzLWFjdGl2ZScpLmluZGV4KCk7XG4gICAgICAgIHBhZ2VzLnJlbW92ZUNsYXNzKCdpcy1hY3RpdmUnKTtcbiAgICAgICAgcGFnZXMuZXEoaW5kZXgpLmFkZENsYXNzKCdpcy1hY3RpdmUnKTtcbiAgICB9KTtcblxuICAgIC8qIExhenlsb2FkaW5nXG4gICAgJCgnLnNsaWRlc2hvdycpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgIHZhciBzbGlkZXNob3c9JCh0aGlzKTtcbiAgICAgIHZhciBpbWFnZXM9c2xpZGVzaG93LmZpbmQoJy5pbWFnZScpLm5vdCgnLmlzLWxvYWRlZCcpO1xuICAgICAgaW1hZ2VzLm9uKCdsb2FkZWQnLGZ1bmN0aW9uKCl7XG4gICAgICAgIHZhciBpbWFnZT0kKHRoaXMpO1xuICAgICAgICB2YXIgc2xpZGU9aW1hZ2UuY2xvc2VzdCgnLnNsaWRlJyk7XG4gICAgICAgIHNsaWRlLmFkZENsYXNzKCdpcy1sb2FkZWQnKTtcbiAgICAgIH0pO1xuICAgICovXG5cbiAgICB2YXIgdGltZW91dCA9IHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICBjb25zb2xlLmxvZyhcIk5leHRcIik7XG4gICAgICAgIHNsaWRlc2hvd05leHQoc2xpZGVzaG93LCBmYWxzZSwgdHJ1ZSk7XG4gICAgfSwgc2xpZGVzaG93RHVyYXRpb24pO1xuXG4gICAgc2xpZGVzaG93LmRhdGEoJ3RpbWVvdXQnLCB0aW1lb3V0KTtcbn0pO1xuXG5pZiAoJCgnLm1haW4tY29udGVudCAuc2xpZGVzaG93JykubGVuZ3RoID4gMSkge1xuICAgICQod2luZG93KS5vbignc2Nyb2xsJywgaG9tZVNsaWRlc2hvd1BhcmFsbGF4KTtcbn1cbmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJET01Db250ZW50TG9hZGVkXCIsICgpID0+IHtcbiAgICAkKCcuc2xpZGUnKS5hZGRDbGFzcygnaXMtbG9hZGVkJyk7XG59KTtcbi8vIGltcG9ydCBTcGxpZGUgZnJvbSAnQHNwbGlkZWpzL3NwbGlkZSc7XG4vLyAvLyBpbXBvcnQgU3dpcGVyIGZyb20gJ3Rpbnktc3dpcGVyJ1xuLy9cbi8vIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJET01Db250ZW50TG9hZGVkXCIsICgpID0+IHtcbi8vICAgICBjb25zb2xlLmxvZyhcImRvY3VtZW50IGxvYWRlZFwiKTtcbi8vICAgICB2YXIgc3BsaWRlID0gbmV3IFNwbGlkZSggJy5zcGxpZGUnLCB7XG4vLyAgICAgICAgIHR5cGUgIDogJ2xvb3AnLFxuLy8gICAgICAgICBzcGVlZDogNjAwLFxuLy8gICAgICAgICByZXdpbmQ6IHRydWUsXG4vLyAgICAgICAgIGxhenlMb2FkOiAnbmVhcmJ5Jyxcbi8vICAgICAgICAgYXV0b3BsYXk6IHRydWUsXG4vLyAgICAgfSApO1xuLy8gICAgIHNwbGlkZS5tb3VudCgpO1xuLy8gfSk7XG4vL1xuLy8gJChkb2N1bWVudCkub24oJ3Z1ZS1sb2FkZWQnLCBmdW5jdGlvbiAoKSB7XG4vLyAgICAgY29uc29sZS5sb2coXCJWdWUgTG9hZGV3ZFwiKTtcbi8vXG4vLyAgICAgLy8gY29uc3Qgc3dpcGVyID0gbmV3IFN3aXBlcihcIi5zd2lwZXItY29udGFpbmVyXCIpO1xuLy8gfSk7Il0sImZpbGUiOiIuL1Jlc291cmNlcy9hc3NldHMvanMvc2xpZGVyLmpzLmpzIiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./Resources/assets/js/slider.js\n");

/***/ }),

/***/ "./resources/assets/sass/app.scss":
/*!****************************************!*\
  !*** ./resources/assets/sass/app.scss ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvYXNzZXRzL3Nhc3MvYXBwLnNjc3MuanMiLCJtYXBwaW5ncyI6IjtBQUFBIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vcmVzb3VyY2VzL2Fzc2V0cy9zYXNzL2FwcC5zY3NzPzNkZDQiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gZXh0cmFjdGVkIGJ5IG1pbmktY3NzLWV4dHJhY3QtcGx1Z2luXG5leHBvcnQge307Il0sIm5hbWVzIjpbXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/assets/sass/app.scss\n");

/***/ }),

/***/ "./resources/assets/sass/admin/app.scss":
/*!**********************************************!*\
  !*** ./resources/assets/sass/admin/app.scss ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvYXNzZXRzL3Nhc3MvYWRtaW4vYXBwLnNjc3MuanMiLCJtYXBwaW5ncyI6IjtBQUFBIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vcmVzb3VyY2VzL2Fzc2V0cy9zYXNzL2FkbWluL2FwcC5zY3NzP2RhZGEiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gZXh0cmFjdGVkIGJ5IG1pbmktY3NzLWV4dHJhY3QtcGx1Z2luXG5leHBvcnQge307Il0sIm5hbWVzIjpbXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/assets/sass/admin/app.scss\n");

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["css/admin-app","css/app"], () => (__webpack_exec__("./Resources/assets/js/slider.js"), __webpack_exec__("./resources/assets/sass/app.scss"), __webpack_exec__("./resources/assets/sass/admin/app.scss")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);