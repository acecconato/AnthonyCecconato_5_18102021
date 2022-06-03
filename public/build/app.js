(self["webpackChunk"] = self["webpackChunk"] || []).push([["app"],{

/***/ "./assets/app.js":
/*!***********************!*\
  !*** ./assets/app.js ***!
  \***********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _js_vendor_theme_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./js/vendor/theme.js */ "./assets/js/vendor/theme.js");
/* harmony import */ var _js_vendor_theme_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_js_vendor_theme_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _scss_main_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./scss/main.scss */ "./assets/scss/main.scss");
/* harmony import */ var _ckeditor_ckeditor5_build_classic__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @ckeditor/ckeditor5-build-classic */ "./node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js");
/* harmony import */ var _ckeditor_ckeditor5_build_classic__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_ckeditor_ckeditor5_build_classic__WEBPACK_IMPORTED_MODULE_2__);
// All things loaded by this entrypoint is available everywhere




/***/ }),

/***/ "./assets/js/vendor/theme.js":
/*!***********************************!*\
  !*** ./assets/js/vendor/theme.js ***!
  \***********************************/
/***/ (() => {

/*!
* Start Bootstrap - Clean Blog v6.0.8 (https://startbootstrap.com/theme/clean-blog)
* Copyright 2013-2022 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-clean-blog/blob/master/LICENSE)
*/
window.addEventListener('DOMContentLoaded', function () {
  var scrollPos = 0;
  var mainNav = document.getElementById('mainNav');
  var headerHeight = mainNav.clientHeight;
  window.addEventListener('scroll', function () {
    var currentTop = document.body.getBoundingClientRect().top * -1;

    if (currentTop < scrollPos) {
      // Scrolling Up
      if (currentTop > 0 && mainNav.classList.contains('is-fixed')) {
        mainNav.classList.add('is-visible');
      } else {
        console.log(123);
        mainNav.classList.remove('is-visible', 'is-fixed');
      }
    } else {
      // Scrolling Down
      mainNav.classList.remove(['is-visible']);

      if (currentTop > headerHeight && !mainNav.classList.contains('is-fixed')) {
        mainNav.classList.add('is-fixed');
      }
    }

    scrollPos = currentTop;
  });
});

/***/ }),

/***/ "./assets/scss/main.scss":
/*!*******************************!*\
  !*** ./assets/scss/main.scss ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_ckeditor_ckeditor5-build-classic_build_ckeditor_js"], () => (__webpack_exec__("./assets/app.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYXBwLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7OztBQUFBO0FBQ0E7QUFFQTs7Ozs7Ozs7Ozs7QUNIQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0FBLE1BQU0sQ0FBQ0MsZ0JBQVAsQ0FBd0Isa0JBQXhCLEVBQTRDLFlBQU07RUFDOUMsSUFBSUMsU0FBUyxHQUFHLENBQWhCO0VBQ0EsSUFBTUMsT0FBTyxHQUFHQyxRQUFRLENBQUNDLGNBQVQsQ0FBd0IsU0FBeEIsQ0FBaEI7RUFDQSxJQUFNQyxZQUFZLEdBQUdILE9BQU8sQ0FBQ0ksWUFBN0I7RUFDQVAsTUFBTSxDQUFDQyxnQkFBUCxDQUF3QixRQUF4QixFQUFrQyxZQUFXO0lBQ3pDLElBQU1PLFVBQVUsR0FBR0osUUFBUSxDQUFDSyxJQUFULENBQWNDLHFCQUFkLEdBQXNDQyxHQUF0QyxHQUE0QyxDQUFDLENBQWhFOztJQUNBLElBQUtILFVBQVUsR0FBR04sU0FBbEIsRUFBNkI7TUFDekI7TUFDQSxJQUFJTSxVQUFVLEdBQUcsQ0FBYixJQUFrQkwsT0FBTyxDQUFDUyxTQUFSLENBQWtCQyxRQUFsQixDQUEyQixVQUEzQixDQUF0QixFQUE4RDtRQUMxRFYsT0FBTyxDQUFDUyxTQUFSLENBQWtCRSxHQUFsQixDQUFzQixZQUF0QjtNQUNILENBRkQsTUFFTztRQUNIQyxPQUFPLENBQUNDLEdBQVIsQ0FBWSxHQUFaO1FBQ0FiLE9BQU8sQ0FBQ1MsU0FBUixDQUFrQkssTUFBbEIsQ0FBeUIsWUFBekIsRUFBdUMsVUFBdkM7TUFDSDtJQUNKLENBUkQsTUFRTztNQUNIO01BQ0FkLE9BQU8sQ0FBQ1MsU0FBUixDQUFrQkssTUFBbEIsQ0FBeUIsQ0FBQyxZQUFELENBQXpCOztNQUNBLElBQUlULFVBQVUsR0FBR0YsWUFBYixJQUE2QixDQUFDSCxPQUFPLENBQUNTLFNBQVIsQ0FBa0JDLFFBQWxCLENBQTJCLFVBQTNCLENBQWxDLEVBQTBFO1FBQ3RFVixPQUFPLENBQUNTLFNBQVIsQ0FBa0JFLEdBQWxCLENBQXNCLFVBQXRCO01BQ0g7SUFDSjs7SUFDRFosU0FBUyxHQUFHTSxVQUFaO0VBQ0gsQ0FsQkQ7QUFtQkgsQ0F2QkQ7Ozs7Ozs7Ozs7OztBQ0xBIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2FwcC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdmVuZG9yL3RoZW1lLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9zY3NzL21haW4uc2NzcyJdLCJzb3VyY2VzQ29udGVudCI6WyIvLyBBbGwgdGhpbmdzIGxvYWRlZCBieSB0aGlzIGVudHJ5cG9pbnQgaXMgYXZhaWxhYmxlIGV2ZXJ5d2hlcmVcclxuaW1wb3J0ICcuL2pzL3ZlbmRvci90aGVtZS5qcyc7XHJcblxyXG5pbXBvcnQgJy4vc2Nzcy9tYWluLnNjc3MnO1xyXG5cclxuaW1wb3J0ICdAY2tlZGl0b3IvY2tlZGl0b3I1LWJ1aWxkLWNsYXNzaWMnIiwiLyohXHJcbiogU3RhcnQgQm9vdHN0cmFwIC0gQ2xlYW4gQmxvZyB2Ni4wLjggKGh0dHBzOi8vc3RhcnRib290c3RyYXAuY29tL3RoZW1lL2NsZWFuLWJsb2cpXHJcbiogQ29weXJpZ2h0IDIwMTMtMjAyMiBTdGFydCBCb290c3RyYXBcclxuKiBMaWNlbnNlZCB1bmRlciBNSVQgKGh0dHBzOi8vZ2l0aHViLmNvbS9TdGFydEJvb3RzdHJhcC9zdGFydGJvb3RzdHJhcC1jbGVhbi1ibG9nL2Jsb2IvbWFzdGVyL0xJQ0VOU0UpXHJcbiovXHJcbndpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgKCkgPT4ge1xyXG4gICAgbGV0IHNjcm9sbFBvcyA9IDA7XHJcbiAgICBjb25zdCBtYWluTmF2ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ21haW5OYXYnKTtcclxuICAgIGNvbnN0IGhlYWRlckhlaWdodCA9IG1haW5OYXYuY2xpZW50SGVpZ2h0O1xyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3Njcm9sbCcsIGZ1bmN0aW9uKCkge1xyXG4gICAgICAgIGNvbnN0IGN1cnJlbnRUb3AgPSBkb2N1bWVudC5ib2R5LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLnRvcCAqIC0xO1xyXG4gICAgICAgIGlmICggY3VycmVudFRvcCA8IHNjcm9sbFBvcykge1xyXG4gICAgICAgICAgICAvLyBTY3JvbGxpbmcgVXBcclxuICAgICAgICAgICAgaWYgKGN1cnJlbnRUb3AgPiAwICYmIG1haW5OYXYuY2xhc3NMaXN0LmNvbnRhaW5zKCdpcy1maXhlZCcpKSB7XHJcbiAgICAgICAgICAgICAgICBtYWluTmF2LmNsYXNzTGlzdC5hZGQoJ2lzLXZpc2libGUnKTtcclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKDEyMyk7XHJcbiAgICAgICAgICAgICAgICBtYWluTmF2LmNsYXNzTGlzdC5yZW1vdmUoJ2lzLXZpc2libGUnLCAnaXMtZml4ZWQnKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIC8vIFNjcm9sbGluZyBEb3duXHJcbiAgICAgICAgICAgIG1haW5OYXYuY2xhc3NMaXN0LnJlbW92ZShbJ2lzLXZpc2libGUnXSk7XHJcbiAgICAgICAgICAgIGlmIChjdXJyZW50VG9wID4gaGVhZGVySGVpZ2h0ICYmICFtYWluTmF2LmNsYXNzTGlzdC5jb250YWlucygnaXMtZml4ZWQnKSkge1xyXG4gICAgICAgICAgICAgICAgbWFpbk5hdi5jbGFzc0xpc3QuYWRkKCdpcy1maXhlZCcpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHNjcm9sbFBvcyA9IGN1cnJlbnRUb3A7XHJcbiAgICB9KTtcclxufSlcclxuIiwiLy8gZXh0cmFjdGVkIGJ5IG1pbmktY3NzLWV4dHJhY3QtcGx1Z2luXG5leHBvcnQge307Il0sIm5hbWVzIjpbIndpbmRvdyIsImFkZEV2ZW50TGlzdGVuZXIiLCJzY3JvbGxQb3MiLCJtYWluTmF2IiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsImhlYWRlckhlaWdodCIsImNsaWVudEhlaWdodCIsImN1cnJlbnRUb3AiLCJib2R5IiwiZ2V0Qm91bmRpbmdDbGllbnRSZWN0IiwidG9wIiwiY2xhc3NMaXN0IiwiY29udGFpbnMiLCJhZGQiLCJjb25zb2xlIiwibG9nIiwicmVtb3ZlIl0sInNvdXJjZVJvb3QiOiIifQ==