(self["webpackChunk"] = self["webpackChunk"] || []).push([["app"],{

/***/ "./assets/app.js":
/*!***********************!*\
  !*** ./assets/app.js ***!
  \***********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_main_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./scss/main.scss */ "./assets/scss/main.scss");
/* harmony import */ var _js_vendor_theme_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./js/vendor/theme.js */ "./assets/js/vendor/theme.js");
/* harmony import */ var _js_vendor_theme_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_js_vendor_theme_js__WEBPACK_IMPORTED_MODULE_1__);
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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYXBwLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7QUFBQTtBQUNBOzs7Ozs7Ozs7OztBQ0RBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQUEsTUFBTSxDQUFDQyxnQkFBUCxDQUF3QixrQkFBeEIsRUFBNEMsWUFBTTtBQUM5QyxNQUFJQyxTQUFTLEdBQUcsQ0FBaEI7QUFDQSxNQUFNQyxPQUFPLEdBQUdDLFFBQVEsQ0FBQ0MsY0FBVCxDQUF3QixTQUF4QixDQUFoQjtBQUNBLE1BQU1DLFlBQVksR0FBR0gsT0FBTyxDQUFDSSxZQUE3QjtBQUNBUCxFQUFBQSxNQUFNLENBQUNDLGdCQUFQLENBQXdCLFFBQXhCLEVBQWtDLFlBQVc7QUFDekMsUUFBTU8sVUFBVSxHQUFHSixRQUFRLENBQUNLLElBQVQsQ0FBY0MscUJBQWQsR0FBc0NDLEdBQXRDLEdBQTRDLENBQUMsQ0FBaEU7O0FBQ0EsUUFBS0gsVUFBVSxHQUFHTixTQUFsQixFQUE2QjtBQUN6QjtBQUNBLFVBQUlNLFVBQVUsR0FBRyxDQUFiLElBQWtCTCxPQUFPLENBQUNTLFNBQVIsQ0FBa0JDLFFBQWxCLENBQTJCLFVBQTNCLENBQXRCLEVBQThEO0FBQzFEVixRQUFBQSxPQUFPLENBQUNTLFNBQVIsQ0FBa0JFLEdBQWxCLENBQXNCLFlBQXRCO0FBQ0gsT0FGRCxNQUVPO0FBQ0hDLFFBQUFBLE9BQU8sQ0FBQ0MsR0FBUixDQUFZLEdBQVo7QUFDQWIsUUFBQUEsT0FBTyxDQUFDUyxTQUFSLENBQWtCSyxNQUFsQixDQUF5QixZQUF6QixFQUF1QyxVQUF2QztBQUNIO0FBQ0osS0FSRCxNQVFPO0FBQ0g7QUFDQWQsTUFBQUEsT0FBTyxDQUFDUyxTQUFSLENBQWtCSyxNQUFsQixDQUF5QixDQUFDLFlBQUQsQ0FBekI7O0FBQ0EsVUFBSVQsVUFBVSxHQUFHRixZQUFiLElBQTZCLENBQUNILE9BQU8sQ0FBQ1MsU0FBUixDQUFrQkMsUUFBbEIsQ0FBMkIsVUFBM0IsQ0FBbEMsRUFBMEU7QUFDdEVWLFFBQUFBLE9BQU8sQ0FBQ1MsU0FBUixDQUFrQkUsR0FBbEIsQ0FBc0IsVUFBdEI7QUFDSDtBQUNKOztBQUNEWixJQUFBQSxTQUFTLEdBQUdNLFVBQVo7QUFDSCxHQWxCRDtBQW1CSCxDQXZCRDs7Ozs7Ozs7Ozs7O0FDTEEiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvYXBwLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy92ZW5kb3IvdGhlbWUuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL3Njc3MvbWFpbi5zY3NzIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIEFsbCB0aGluZ3MgbG9hZGVkIGJ5IHRoaXMgZW50cnlwb2ludCBpcyBhdmFpbGFibGUgZXZlcnl3aGVyZVxyXG5pbXBvcnQgJy4vc2Nzcy9tYWluLnNjc3MnO1xyXG5pbXBvcnQgJy4vanMvdmVuZG9yL3RoZW1lLmpzJzsiLCIvKiFcbiogU3RhcnQgQm9vdHN0cmFwIC0gQ2xlYW4gQmxvZyB2Ni4wLjggKGh0dHBzOi8vc3RhcnRib290c3RyYXAuY29tL3RoZW1lL2NsZWFuLWJsb2cpXG4qIENvcHlyaWdodCAyMDEzLTIwMjIgU3RhcnQgQm9vdHN0cmFwXG4qIExpY2Vuc2VkIHVuZGVyIE1JVCAoaHR0cHM6Ly9naXRodWIuY29tL1N0YXJ0Qm9vdHN0cmFwL3N0YXJ0Ym9vdHN0cmFwLWNsZWFuLWJsb2cvYmxvYi9tYXN0ZXIvTElDRU5TRSlcbiovXG53aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsICgpID0+IHtcbiAgICBsZXQgc2Nyb2xsUG9zID0gMDtcbiAgICBjb25zdCBtYWluTmF2ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ21haW5OYXYnKTtcbiAgICBjb25zdCBoZWFkZXJIZWlnaHQgPSBtYWluTmF2LmNsaWVudEhlaWdodDtcbiAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignc2Nyb2xsJywgZnVuY3Rpb24oKSB7XG4gICAgICAgIGNvbnN0IGN1cnJlbnRUb3AgPSBkb2N1bWVudC5ib2R5LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLnRvcCAqIC0xO1xuICAgICAgICBpZiAoIGN1cnJlbnRUb3AgPCBzY3JvbGxQb3MpIHtcbiAgICAgICAgICAgIC8vIFNjcm9sbGluZyBVcFxuICAgICAgICAgICAgaWYgKGN1cnJlbnRUb3AgPiAwICYmIG1haW5OYXYuY2xhc3NMaXN0LmNvbnRhaW5zKCdpcy1maXhlZCcpKSB7XG4gICAgICAgICAgICAgICAgbWFpbk5hdi5jbGFzc0xpc3QuYWRkKCdpcy12aXNpYmxlJyk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKDEyMyk7XG4gICAgICAgICAgICAgICAgbWFpbk5hdi5jbGFzc0xpc3QucmVtb3ZlKCdpcy12aXNpYmxlJywgJ2lzLWZpeGVkJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAvLyBTY3JvbGxpbmcgRG93blxuICAgICAgICAgICAgbWFpbk5hdi5jbGFzc0xpc3QucmVtb3ZlKFsnaXMtdmlzaWJsZSddKTtcbiAgICAgICAgICAgIGlmIChjdXJyZW50VG9wID4gaGVhZGVySGVpZ2h0ICYmICFtYWluTmF2LmNsYXNzTGlzdC5jb250YWlucygnaXMtZml4ZWQnKSkge1xuICAgICAgICAgICAgICAgIG1haW5OYXYuY2xhc3NMaXN0LmFkZCgnaXMtZml4ZWQnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBzY3JvbGxQb3MgPSBjdXJyZW50VG9wO1xuICAgIH0pO1xufSlcbiIsIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpblxuZXhwb3J0IHt9OyJdLCJuYW1lcyI6WyJ3aW5kb3ciLCJhZGRFdmVudExpc3RlbmVyIiwic2Nyb2xsUG9zIiwibWFpbk5hdiIsImRvY3VtZW50IiwiZ2V0RWxlbWVudEJ5SWQiLCJoZWFkZXJIZWlnaHQiLCJjbGllbnRIZWlnaHQiLCJjdXJyZW50VG9wIiwiYm9keSIsImdldEJvdW5kaW5nQ2xpZW50UmVjdCIsInRvcCIsImNsYXNzTGlzdCIsImNvbnRhaW5zIiwiYWRkIiwiY29uc29sZSIsImxvZyIsInJlbW92ZSJdLCJzb3VyY2VSb290IjoiIn0=