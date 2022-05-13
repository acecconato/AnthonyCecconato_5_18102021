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
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/app.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYXBwLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7QUFBQTtBQUNBOzs7Ozs7Ozs7OztBQ0RBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQUEsTUFBTSxDQUFDQyxnQkFBUCxDQUF3QixrQkFBeEIsRUFBNEMsWUFBTTtFQUM5QyxJQUFJQyxTQUFTLEdBQUcsQ0FBaEI7RUFDQSxJQUFNQyxPQUFPLEdBQUdDLFFBQVEsQ0FBQ0MsY0FBVCxDQUF3QixTQUF4QixDQUFoQjtFQUNBLElBQU1DLFlBQVksR0FBR0gsT0FBTyxDQUFDSSxZQUE3QjtFQUNBUCxNQUFNLENBQUNDLGdCQUFQLENBQXdCLFFBQXhCLEVBQWtDLFlBQVc7SUFDekMsSUFBTU8sVUFBVSxHQUFHSixRQUFRLENBQUNLLElBQVQsQ0FBY0MscUJBQWQsR0FBc0NDLEdBQXRDLEdBQTRDLENBQUMsQ0FBaEU7O0lBQ0EsSUFBS0gsVUFBVSxHQUFHTixTQUFsQixFQUE2QjtNQUN6QjtNQUNBLElBQUlNLFVBQVUsR0FBRyxDQUFiLElBQWtCTCxPQUFPLENBQUNTLFNBQVIsQ0FBa0JDLFFBQWxCLENBQTJCLFVBQTNCLENBQXRCLEVBQThEO1FBQzFEVixPQUFPLENBQUNTLFNBQVIsQ0FBa0JFLEdBQWxCLENBQXNCLFlBQXRCO01BQ0gsQ0FGRCxNQUVPO1FBQ0hDLE9BQU8sQ0FBQ0MsR0FBUixDQUFZLEdBQVo7UUFDQWIsT0FBTyxDQUFDUyxTQUFSLENBQWtCSyxNQUFsQixDQUF5QixZQUF6QixFQUF1QyxVQUF2QztNQUNIO0lBQ0osQ0FSRCxNQVFPO01BQ0g7TUFDQWQsT0FBTyxDQUFDUyxTQUFSLENBQWtCSyxNQUFsQixDQUF5QixDQUFDLFlBQUQsQ0FBekI7O01BQ0EsSUFBSVQsVUFBVSxHQUFHRixZQUFiLElBQTZCLENBQUNILE9BQU8sQ0FBQ1MsU0FBUixDQUFrQkMsUUFBbEIsQ0FBMkIsVUFBM0IsQ0FBbEMsRUFBMEU7UUFDdEVWLE9BQU8sQ0FBQ1MsU0FBUixDQUFrQkUsR0FBbEIsQ0FBc0IsVUFBdEI7TUFDSDtJQUNKOztJQUNEWixTQUFTLEdBQUdNLFVBQVo7RUFDSCxDQWxCRDtBQW1CSCxDQXZCRDs7Ozs7Ozs7Ozs7O0FDTEEiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvYXBwLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy92ZW5kb3IvdGhlbWUuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL3Njc3MvbWFpbi5zY3NzIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIEFsbCB0aGluZ3MgbG9hZGVkIGJ5IHRoaXMgZW50cnlwb2ludCBpcyBhdmFpbGFibGUgZXZlcnl3aGVyZVxyXG5pbXBvcnQgJy4vanMvdmVuZG9yL3RoZW1lLmpzJztcclxuXHJcbmltcG9ydCAnLi9zY3NzL21haW4uc2Nzcyc7IiwiLyohXHJcbiogU3RhcnQgQm9vdHN0cmFwIC0gQ2xlYW4gQmxvZyB2Ni4wLjggKGh0dHBzOi8vc3RhcnRib290c3RyYXAuY29tL3RoZW1lL2NsZWFuLWJsb2cpXHJcbiogQ29weXJpZ2h0IDIwMTMtMjAyMiBTdGFydCBCb290c3RyYXBcclxuKiBMaWNlbnNlZCB1bmRlciBNSVQgKGh0dHBzOi8vZ2l0aHViLmNvbS9TdGFydEJvb3RzdHJhcC9zdGFydGJvb3RzdHJhcC1jbGVhbi1ibG9nL2Jsb2IvbWFzdGVyL0xJQ0VOU0UpXHJcbiovXHJcbndpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgKCkgPT4ge1xyXG4gICAgbGV0IHNjcm9sbFBvcyA9IDA7XHJcbiAgICBjb25zdCBtYWluTmF2ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ21haW5OYXYnKTtcclxuICAgIGNvbnN0IGhlYWRlckhlaWdodCA9IG1haW5OYXYuY2xpZW50SGVpZ2h0O1xyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3Njcm9sbCcsIGZ1bmN0aW9uKCkge1xyXG4gICAgICAgIGNvbnN0IGN1cnJlbnRUb3AgPSBkb2N1bWVudC5ib2R5LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLnRvcCAqIC0xO1xyXG4gICAgICAgIGlmICggY3VycmVudFRvcCA8IHNjcm9sbFBvcykge1xyXG4gICAgICAgICAgICAvLyBTY3JvbGxpbmcgVXBcclxuICAgICAgICAgICAgaWYgKGN1cnJlbnRUb3AgPiAwICYmIG1haW5OYXYuY2xhc3NMaXN0LmNvbnRhaW5zKCdpcy1maXhlZCcpKSB7XHJcbiAgICAgICAgICAgICAgICBtYWluTmF2LmNsYXNzTGlzdC5hZGQoJ2lzLXZpc2libGUnKTtcclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKDEyMyk7XHJcbiAgICAgICAgICAgICAgICBtYWluTmF2LmNsYXNzTGlzdC5yZW1vdmUoJ2lzLXZpc2libGUnLCAnaXMtZml4ZWQnKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIC8vIFNjcm9sbGluZyBEb3duXHJcbiAgICAgICAgICAgIG1haW5OYXYuY2xhc3NMaXN0LnJlbW92ZShbJ2lzLXZpc2libGUnXSk7XHJcbiAgICAgICAgICAgIGlmIChjdXJyZW50VG9wID4gaGVhZGVySGVpZ2h0ICYmICFtYWluTmF2LmNsYXNzTGlzdC5jb250YWlucygnaXMtZml4ZWQnKSkge1xyXG4gICAgICAgICAgICAgICAgbWFpbk5hdi5jbGFzc0xpc3QuYWRkKCdpcy1maXhlZCcpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHNjcm9sbFBvcyA9IGN1cnJlbnRUb3A7XHJcbiAgICB9KTtcclxufSlcclxuIiwiLy8gZXh0cmFjdGVkIGJ5IG1pbmktY3NzLWV4dHJhY3QtcGx1Z2luXG5leHBvcnQge307Il0sIm5hbWVzIjpbIndpbmRvdyIsImFkZEV2ZW50TGlzdGVuZXIiLCJzY3JvbGxQb3MiLCJtYWluTmF2IiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsImhlYWRlckhlaWdodCIsImNsaWVudEhlaWdodCIsImN1cnJlbnRUb3AiLCJib2R5IiwiZ2V0Qm91bmRpbmdDbGllbnRSZWN0IiwidG9wIiwiY2xhc3NMaXN0IiwiY29udGFpbnMiLCJhZGQiLCJjb25zb2xlIiwibG9nIiwicmVtb3ZlIl0sInNvdXJjZVJvb3QiOiIifQ==