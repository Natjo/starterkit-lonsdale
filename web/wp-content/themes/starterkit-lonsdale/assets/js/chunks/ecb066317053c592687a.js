"use strict";
(self["webpackChunkwordpress_starter_kit"] = self["webpackChunkwordpress_starter_kit"] || []).push([["assets_js_modules_slider_js"],{

/***/ "./assets/js/modules/slider.js":
/*!*************************************!*\
  !*** ./assets/js/modules/slider.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

/* eslint-disable */

/**
 * @module Slider
 * @param {HTMLElement} el 
 * 
 */
// Change animation by scrollIntoView when supported
function Slider(slider) {
  var isTouch = ('ontouchstart' in document.documentElement);
  var content = slider.querySelector('.slider-content');
  var items = content.querySelectorAll('.item');
  var pagination = slider.querySelector('.slider-pagination');
  var btn_next = slider.querySelector('.next');
  var btn_prev = slider.querySelector('.prev');
  var focusEls = slider.querySelectorAll('.item a,.item button,.item input');
  var bullets = [];
  var offsetX = 0;
  var index = 0;
  var downX;
  var length = 1;
  var paddingLeft;

  var getLength = function getLength() {
    length = 0;

    for (var i = 0; i < items.length; i++) {
      if (items[i].offsetLeft < content.scrollWidth - content.offsetWidth + paddingLeft) {
        length++;
      }
    }
  };

  getLength();
  focusEls.forEach(function (el) {
    el.onfocus = function () {
      var item = el.closest('.item');
      index = _toConsumableArray(item.parentNode.children).indexOf(item);
      if (index > length) index = length;
      goto();
    };
  });

  if (pagination) {
    for (var i = 0; i < items.length; i++) {
      var bullet = document.createElement('button');
      bullet.value = i;
      bullet.setAttribute('aria-hidden', true);
      bullet.setAttribute('tabindex', -1);
      pagination.appendChild(bullet);
      bullets.push(bullet);
    }
  }

  function fakeScrollTo(end) {
    var req;
    var init = null;
    var time;
    var start = content.scrollLeft;
    var duration = 600;

    var easing = function easing(t, b, c, d) {
      return -c * ((t = t / d - 1) * t * t * t - 1) + b;
    };

    var startAnim = function startAnim(timeStamp) {
      init = timeStamp;
      draw(timeStamp);
    };

    var draw = function draw(now) {
      time = now - init;
      content.scrollTo(easing(time, start, end - start, duration), 0);
      req = window.requestAnimationFrame(draw);
      time >= duration && window.cancelAnimationFrame(req);
    };

    req = window.requestAnimationFrame(startAnim);
  }

  ;

  var mouseMove = function mouseMove(e) {
    content.classList.add('onswipe');
    content.scrollTo(-e.clientX + offsetX, 0);
  };

  var resize = function resize() {
    slider.style.setProperty('--ctr-left', "".concat(slider.getBoundingClientRect().left, "px"));
    slider.style.setProperty('--ctr-width', "".concat(slider.offsetWidth, "px"));
    paddingLeft = slider.offsetLeft + parseInt(getComputedStyle(slider).getPropertyValue('--padding-left') * 10);
    getLength();
    goto();
  };

  function controlStatus() {
    if (pagination) {
      for (var _i = 0; _i < bullets.length; _i++) {
        bullets[_i].classList[_i === index ? 'add' : 'remove']('active');

        bullets[_i].classList[_i <= length ? 'remove' : 'add']('hide');
      }

      if (bullets.length == 1) bullets[0].classList.add('hide');
    }

    if (btn_prev) {
      if (index <= 0) btn_prev.setAttribute('aria-disabled', true);else btn_prev.removeAttribute('aria-disabled');
      btn_prev.classList[length === 0 ? 'add' : 'remove']('hide');
    }

    if (btn_next) {
      if (index == length) btn_next.setAttribute('aria-disabled', true);else btn_next.removeAttribute('aria-disabled');
      btn_next.classList[length === 0 ? 'add' : 'remove']('hide');
    }
  }

  var goto = function goto() {
    controlStatus();
    var itempos = items[index].offsetLeft - paddingLeft;
    var diff = itempos - (content.scrollWidth - content.offsetWidth);
    if (diff < 0) diff = 0;
    fakeScrollTo(itempos - diff);
    /*content.scrollTo({
        left: itempos - diff,
        behavior: 'smooth'
    });*/
  };

  function getIndex() {
    items.forEach(function (item) {
      if (item.offsetLeft < content.scrollLeft + paddingLeft + 100) index = index + 1;
    });
    if (offsetX - downX > content.scrollLeft) index = index - 1;
    if (index <= 0) index = 0;
    if (index > length) index = length;
  }

  var mouseUp = function mouseUp(e) {
    index = 0;
    getIndex();
    goto();
    window.removeEventListener('mousemove', mouseMove);
    window.removeEventListener('mouseup', mouseUp);
    content.classList.remove('onswipe');
  };

  var mouseDown = function mouseDown(val) {
    downX = val;
    offsetX = downX + content.scrollLeft;
    window.addEventListener('mousemove', mouseMove);
    window.addEventListener('mouseup', mouseUp);
    return false;
  };

  var next = function next() {
    index++;
    if (index >= length) index = length;
    goto();
  };

  var prev = function prev() {
    index--;
    if (index <= 0) index = 0;
    goto();
  };

  if (btn_next) btn_next.onclick = function () {
    return next();
  };
  if (btn_prev) btn_prev.onclick = function () {
    return prev();
  };
  bullets.forEach(function (bullet) {
    bullet.onclick = function () {
      index = Number(bullet.value);
      goto();
    };
  });

  this.enable = function () {
    slider.classList.add('slider');
    index = 0;
    resize();

    if (!isTouch) {
      content.onmousedown = function (e) {
        return mouseDown(e.clientX);
      };

      window.addEventListener('resize', resize, {
        passive: true
      });
    } else {
      content.classList.add('touchable');
      content.addEventListener("scroll", function () {
        index = -1;
        getIndex();
        controlStatus();
      }, {
        passive: true
      });
    }
  };

  this.disable = function () {
    slider.classList.remove('slider');
    content.onmousedown = null;
    window.removeEventListener('resize', resize);
    mouseUp();
  };
}

/* harmony default export */ __webpack_exports__["default"] = (Slider);

/***/ })

}]);
//# sourceMappingURL=ecb066317053c592687a.js.map