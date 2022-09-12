"use strict";
(self["webpackChunkwordpress_starter_kit"] = self["webpackChunkwordpress_starter_kit"] || []).push([["assets_js_blocks_header-nav_js"],{

/***/ "./assets/js/blocks/header-nav.js":
/*!****************************************!*\
  !*** ./assets/js/blocks/header-nav.js ***!
  \****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* eslint-disable */
/* harmony default export */ __webpack_exports__["default"] = (function (el) {
  var clicktouch = 'ontouchstart' in document.activeElement ? 'touchstart' : 'click';
  var header = document.getElementById('header');
  var panel = document.getElementById('nav-panel');
  var btn_nav = header.querySelector('#btn-nav');
  var oldtrig,
      trig = 0;
  var triger = 150;

  var change = function change() {
    return header.classList[trig === 1 ? 'add' : 'remove']('trig');
  };

  var scroll = function scroll() {
    scrollY = window.pageYOffset;
    trig = scrollY > triger ? 1 : 0;
    oldtrig !== trig && change();
    oldtrig = trig;
  };

  var open = function open() {
    document.body.classList.add('hasPopin');
    header.classList.add('open');
    btn_nav.setAttribute('aria-expanded', true);
    document.addEventListener('keydown', onKeyDown);
    document.addEventListener('keyup', onKeyUp);
    window.addEventListener(clicktouch, clickOut, true);
  };

  var close = function close() {
    document.body.classList.remove('hasPopin');
    header.classList.remove('open');
    btn_nav.setAttribute('aria-expanded', false);
    document.removeEventListener('keydown', onKeyDown);
    document.removeEventListener('keyup', onKeyUp);
    window.removeEventListener(clicktouch, clickOut);
  };

  var clickOut = function clickOut(e) {
    if (!panel.contains(e.target) && !btn_nav.contains(e.target)) close();
  };

  var onKeyUp = function onKeyUp() {
    if (!panel.contains(document.activeElement) && !btn_nav.contains(document.activeElement)) close();
  };

  var onKeyDown = function onKeyDown(e) {
    if (e.key == 'Escape') {
      close();
      btn_nav.focus();
    }
  };

  btn_nav.onclick = function () {
    return btn_nav.getAttribute('aria-expanded') === 'false' ? open() : close();
  };

  window.addEventListener('scroll', scroll, {
    passive: true
  });
  window.pageYOffset > triger && header.classList.add('show');
});

/***/ })

}]);
//# sourceMappingURL=2c760a048cf26fc52350.js.map