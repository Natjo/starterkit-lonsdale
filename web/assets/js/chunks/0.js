(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./assets/js/blocks/header-nav.js":
/*!****************************************!*\
  !*** ./assets/js/blocks/header-nav.js ***!
  \****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* eslint-disable */
/* harmony default export */ __webpack_exports__["default"] = (function (el) {
  var body = document.querySelector('body');

  function toggleMenuMobile() {
    if (!body.classList.contains('menu-mobile-open')) {
      body.classList.add('menu-mobile-open');
    } else {
      body.classList.remove('menu-mobile-open');
    }
  }

  el.addEventListener('click', toggleMenuMobile);
});

/***/ })

}]);
//# sourceMappingURL=0.js.map