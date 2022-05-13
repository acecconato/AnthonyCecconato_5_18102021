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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYXBwLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7QUFBQTtBQUNBOzs7Ozs7Ozs7OztBQ0RBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQUEsTUFBTSxDQUFDQyxnQkFBUCxDQUF3QixrQkFBeEIsRUFBNEMsWUFBTTtBQUM5QyxNQUFJQyxTQUFTLEdBQUcsQ0FBaEI7QUFDQSxNQUFNQyxPQUFPLEdBQUdDLFFBQVEsQ0FBQ0MsY0FBVCxDQUF3QixTQUF4QixDQUFoQjtBQUNBLE1BQU1DLFlBQVksR0FBR0gsT0FBTyxDQUFDSSxZQUE3QjtBQUNBUCxFQUFBQSxNQUFNLENBQUNDLGdCQUFQLENBQXdCLFFBQXhCLEVBQWtDLFlBQVc7QUFDekMsUUFBTU8sVUFBVSxHQUFHSixRQUFRLENBQUNLLElBQVQsQ0FBY0MscUJBQWQsR0FBc0NDLEdBQXRDLEdBQTRDLENBQUMsQ0FBaEU7O0FBQ0EsUUFBS0gsVUFBVSxHQUFHTixTQUFsQixFQUE2QjtBQUN6QjtBQUNBLFVBQUlNLFVBQVUsR0FBRyxDQUFiLElBQWtCTCxPQUFPLENBQUNTLFNBQVIsQ0FBa0JDLFFBQWxCLENBQTJCLFVBQTNCLENBQXRCLEVBQThEO0FBQzFEVixRQUFBQSxPQUFPLENBQUNTLFNBQVIsQ0FBa0JFLEdBQWxCLENBQXNCLFlBQXRCO0FBQ0gsT0FGRCxNQUVPO0FBQ0hDLFFBQUFBLE9BQU8sQ0FBQ0MsR0FBUixDQUFZLEdBQVo7QUFDQWIsUUFBQUEsT0FBTyxDQUFDUyxTQUFSLENBQWtCSyxNQUFsQixDQUF5QixZQUF6QixFQUF1QyxVQUF2QztBQUNIO0FBQ0osS0FSRCxNQVFPO0FBQ0g7QUFDQWQsTUFBQUEsT0FBTyxDQUFDUyxTQUFSLENBQWtCSyxNQUFsQixDQUF5QixDQUFDLFlBQUQsQ0FBekI7O0FBQ0EsVUFBSVQsVUFBVSxHQUFHRixZQUFiLElBQTZCLENBQUNILE9BQU8sQ0FBQ1MsU0FBUixDQUFrQkMsUUFBbEIsQ0FBMkIsVUFBM0IsQ0FBbEMsRUFBMEU7QUFDdEVWLFFBQUFBLE9BQU8sQ0FBQ1MsU0FBUixDQUFrQkUsR0FBbEIsQ0FBc0IsVUFBdEI7QUFDSDtBQUNKOztBQUNEWixJQUFBQSxTQUFTLEdBQUdNLFVBQVo7QUFDSCxHQWxCRDtBQW1CSCxDQXZCRDs7Ozs7Ozs7Ozs7O0FDTEEiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvYXBwLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy92ZW5kb3IvdGhlbWUuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL3Njc3MvbWFpbi5zY3NzIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIEFsbCB0aGluZ3MgbG9hZGVkIGJ5IHRoaXMgZW50cnlwb2ludCBpcyBhdmFpbGFibGUgZXZlcnl3aGVyZVxyXG5pbXBvcnQgJy4vanMvdmVuZG9yL3RoZW1lLmpzJztcclxuXHJcbmltcG9ydCAnLi9zY3NzL21haW4uc2Nzcyc7IiwiLyohXG4qIFN0YXJ0IEJvb3RzdHJhcCAtIENsZWFuIEJsb2cgdjYuMC44IChodHRwczovL3N0YXJ0Ym9vdHN0cmFwLmNvbS90aGVtZS9jbGVhbi1ibG9nKVxuKiBDb3B5cmlnaHQgMjAxMy0yMDIyIFN0YXJ0IEJvb3RzdHJhcFxuKiBMaWNlbnNlZCB1bmRlciBNSVQgKGh0dHBzOi8vZ2l0aHViLmNvbS9TdGFydEJvb3RzdHJhcC9zdGFydGJvb3RzdHJhcC1jbGVhbi1ibG9nL2Jsb2IvbWFzdGVyL0xJQ0VOU0UpXG4qL1xud2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCAoKSA9PiB7XG4gICAgbGV0IHNjcm9sbFBvcyA9IDA7XG4gICAgY29uc3QgbWFpbk5hdiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdtYWluTmF2Jyk7XG4gICAgY29uc3QgaGVhZGVySGVpZ2h0ID0gbWFpbk5hdi5jbGllbnRIZWlnaHQ7XG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ3Njcm9sbCcsIGZ1bmN0aW9uKCkge1xuICAgICAgICBjb25zdCBjdXJyZW50VG9wID0gZG9jdW1lbnQuYm9keS5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKS50b3AgKiAtMTtcbiAgICAgICAgaWYgKCBjdXJyZW50VG9wIDwgc2Nyb2xsUG9zKSB7XG4gICAgICAgICAgICAvLyBTY3JvbGxpbmcgVXBcbiAgICAgICAgICAgIGlmIChjdXJyZW50VG9wID4gMCAmJiBtYWluTmF2LmNsYXNzTGlzdC5jb250YWlucygnaXMtZml4ZWQnKSkge1xuICAgICAgICAgICAgICAgIG1haW5OYXYuY2xhc3NMaXN0LmFkZCgnaXMtdmlzaWJsZScpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZygxMjMpO1xuICAgICAgICAgICAgICAgIG1haW5OYXYuY2xhc3NMaXN0LnJlbW92ZSgnaXMtdmlzaWJsZScsICdpcy1maXhlZCcpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgLy8gU2Nyb2xsaW5nIERvd25cbiAgICAgICAgICAgIG1haW5OYXYuY2xhc3NMaXN0LnJlbW92ZShbJ2lzLXZpc2libGUnXSk7XG4gICAgICAgICAgICBpZiAoY3VycmVudFRvcCA+IGhlYWRlckhlaWdodCAmJiAhbWFpbk5hdi5jbGFzc0xpc3QuY29udGFpbnMoJ2lzLWZpeGVkJykpIHtcbiAgICAgICAgICAgICAgICBtYWluTmF2LmNsYXNzTGlzdC5hZGQoJ2lzLWZpeGVkJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgc2Nyb2xsUG9zID0gY3VycmVudFRvcDtcbiAgICB9KTtcbn0pXG4iLCIvLyBleHRyYWN0ZWQgYnkgbWluaS1jc3MtZXh0cmFjdC1wbHVnaW5cbmV4cG9ydCB7fTsiXSwibmFtZXMiOlsid2luZG93IiwiYWRkRXZlbnRMaXN0ZW5lciIsInNjcm9sbFBvcyIsIm1haW5OYXYiLCJkb2N1bWVudCIsImdldEVsZW1lbnRCeUlkIiwiaGVhZGVySGVpZ2h0IiwiY2xpZW50SGVpZ2h0IiwiY3VycmVudFRvcCIsImJvZHkiLCJnZXRCb3VuZGluZ0NsaWVudFJlY3QiLCJ0b3AiLCJjbGFzc0xpc3QiLCJjb250YWlucyIsImFkZCIsImNvbnNvbGUiLCJsb2ciLCJyZW1vdmUiXSwic291cmNlUm9vdCI6IiJ9