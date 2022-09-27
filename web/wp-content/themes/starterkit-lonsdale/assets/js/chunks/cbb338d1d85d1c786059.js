"use strict";
(self["webpackChunkwordpress_starter_kit"] = self["webpackChunkwordpress_starter_kit"] || []).push([["assets_js_strates_news_js"],{

/***/ "./assets/js/modules/slider.js":
/*!*************************************!*\
  !*** ./assets/js/modules/slider.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
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
  var btn_next = slider.querySelector('.next');
  var btn_prev = slider.querySelector('.prev');
  var offsetX = 0;
  var index = 0;
  var itemW;
  var downX;
  var gap;
  var nb; // replace if tabulating

  slider.addEventListener('keyup', function () {
    for (var i = 0; i < items.length; i++) {
      if (items[i].contains(document.activeElement)) {
        goto(i);
      }
    }
  }); // Use fakeScrollTo while smooth behavior not fully supported

  function fakeScrollTo(end) {
    var req;
    var init = null;
    var time;
    var start = content.scrollLeft;
    var duration = 500;

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
    gap = parseInt(getComputedStyle(content).gridColumnGap);
    nb = parseInt(getComputedStyle(slider).getPropertyValue('--nb')) || 1;
    itemW = items[0].offsetWidth;
    goto(index);
  };

  var goto = function goto(num) {
    if (!isTouch) {
      fakeScrollTo((itemW + gap) * num);
    } else {
      content.scrollTo({
        left: (itemW + gap) * num,
        behavior: 'smooth'
      });
    }
  };

  var mouseUp = function mouseUp(e) {
    index = 0;
    items.forEach(function (item, i) {
      if (item.offsetLeft - itemW / 2 - gap < content.scrollLeft) index = i;
    });
    goto(index);
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
    if (index >= items.length - nb) index = items.length - nb;
    goto(index);
  };

  var prev = function prev() {
    index--;
    if (index <= 0) index = 0;
    goto(index);
  };

  if (btn_next) btn_next.onclick = function () {
    return next();
  };
  if (btn_prev) btn_prev.onclick = function () {
    return prev();
  };

  this.enable = function () {
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
    }
  };

  this.disable = function () {
    content.onmousedown = null;
    window.removeEventListener('resize', resize);
    mouseUp();
  };
}

/* harmony default export */ __webpack_exports__["default"] = (Slider);

/***/ }),

/***/ "./assets/js/strates/news.js":
/*!***********************************!*\
  !*** ./assets/js/strates/news.js ***!
  \***********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modules_slider_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../modules/slider.js */ "./assets/js/modules/slider.js");
/* eslint-disable */

/* harmony default export */ __webpack_exports__["default"] = (function (el) {
  var slider = el.querySelector(".slider");
  var myslider = new _modules_slider_js__WEBPACK_IMPORTED_MODULE_0__["default"](slider);
  myslider.enable();
});

/***/ })

}]);
//# sourceMappingURL=cbb338d1d85d1c786059.js.map