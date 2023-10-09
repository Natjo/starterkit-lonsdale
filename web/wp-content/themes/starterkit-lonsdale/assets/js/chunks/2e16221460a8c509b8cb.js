"use strict";
(self["webpackChunkwordpress_starter_kit"] = self["webpackChunkwordpress_starter_kit"] || []).push([["assets_js_strates_sliders_js"],{

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
  var isMove = false;
  var number;

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
    !isMove && content.classList.add('onswipe');
    content.scrollTo(-e.clientX + offsetX, 0);
    isMove = true;
  };

  var resize = function resize() {
    slider.style.setProperty('--ctr-left', "".concat(slider.getBoundingClientRect().left, "px"));
    slider.style.setProperty('--ctr-width', "".concat(slider.offsetWidth, "px"));
    paddingLeft = slider.offsetLeft + parseInt(getComputedStyle(slider).getPropertyValue('--padding-left') * 10);
    number = 1 + parseInt(getComputedStyle(slider).getPropertyValue('--nb'));
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

    if (!isTouch) {
      fakeScrollTo(itempos - diff);
    } else {
      content.scrollTo({
        left: itempos - diff,
        behavior: 'smooth'
      });
    }
  };

  function getIndex() {
    index = -1;
    items.forEach(function (item) {
      if (item.offsetLeft < content.scrollLeft + content.offsetWidth / number) index = index + 1;
    });
    if (offsetX - downX > content.scrollLeft) index = index - 1;
    if (index <= 0) index = 0;
    if (index > length) index = length;
  }

  var mouseUp = function mouseUp(e) {
    window.removeEventListener('mousemove', mouseMove);
    window.removeEventListener('mouseup', mouseUp);
    content.classList.remove('onswipe');
    if (!isMove) return;
    getIndex();
    goto();
    isMove = false;
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
      content.addEventListener('scroll', function () {
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

/***/ }),

/***/ "./assets/js/strates/sliders.js":
/*!**************************************!*\
  !*** ./assets/js/strates/sliders.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modules_slider_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../modules/slider.js */ "./assets/js/modules/slider.js");
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

/* eslint-disable */

/* harmony default export */ __webpack_exports__["default"] = (function (el) {
  var sliders = document.querySelectorAll('.slider0,.slider1,.slider2,.slider3,.slider4');

  window.onload = function () {
    sliders.forEach(function (slider) {
      var myscroll = new _modules_slider_js__WEBPACK_IMPORTED_MODULE_0__["default"](slider);
      myscroll.enable();
    });
  }; // slider 5 : disabled on desktop


  var slider5 = document.querySelector('.slider5');
  var myscroll = new _modules_slider_js__WEBPACK_IMPORTED_MODULE_0__["default"](slider5);
  myscroll.enable();
  var once = true;
  var ismobile = false;

  var resize = function resize() {
    ismobile = window.innerWidth > 768 ? false : true;

    if (ismobile != once) {
      ismobile ? myscroll.enable() : myscroll.disable();
    }

    once = ismobile;
  };

  window.addEventListener('resize', resize, {
    passive: true
  });
  resize(); // trains

  var btns_add = el.querySelectorAll('.btn-add');
  var btns_reset = el.querySelectorAll('.btn-reset');
  var inputs = el.querySelectorAll('.strate-content input');
  var datas = {
    TER: {
      name: 'Avignon - Cavaillon',
      go: [{
        depart: '10:41',
        arriver: '11:23'
      }, {
        depart: '12:38',
        arriver: '13:17'
      }, {
        depart: '14:44',
        arriver: '15:25'
      }, {
        depart: '15:13',
        arriver: '17:23'
      }, {
        depart: '15:41',
        arriver: '17:23'
      }, {
        depart: '16:41',
        arriver: '17:32'
      }, {
        depart: '17:10',
        arriver: '18:32'
      }, {
        depart: '20:12',
        arriver: '21:00'
      }],
      back: [{
        depart: '13:25',
        arriver: '14:30'
      }, {
        depart: '16:15',
        arriver: '17:30'
      }]
    },
    915: {
      name: 'Avignon - Vignieres',
      go: [{
        depart: '9:45',
        arriver: '10:26'
      }, {
        depart: '15:55',
        arriver: '16:36'
      }, {
        depart: '16:30',
        arriver: '17:11'
      }, {
        depart: '18:20',
        arriver: '19:01'
      }],
      back: [{
        depart: '8:48',
        arriver: '9:35'
      }, {
        depart: '13:28',
        arriver: '14:15'
      }, {
        depart: '14:03',
        arriver: '14:50'
      }, {
        depart: '17:13',
        arriver: '18:00'
      }]
    }
  };

  function dateDiff(time1, titme2) {
    var date1 = new Date("0001-01-01 ".concat(time1, ":00"));
    var date2 = new Date("0001-01-01 ".concat(titme2, ":00"));
    var diff = {};
    var tmp = date2 - date1;
    tmp = Math.floor(tmp / 1000);
    diff.sec = tmp % 60;
    tmp = Math.floor((tmp - diff.sec) / 60);
    diff.min = tmp % 60;
    tmp = Math.floor((tmp - diff.min) / 60);
    diff.hour = tmp % 24;
    return "".concat(diff.hour > 0 ? diff.hour + 'h' : '').concat(Number(diff.min) > 10 ? '' + diff.min : "0" + diff.min, "min");
  }

  function dateDiff1(time1, titme2) {
    var date1 = new Date("0001-01-01 ".concat(time1, ":00"));
    var date2 = new Date("0001-01-01 ".concat(titme2, ":00"));
    var diff = {};
    var tmp = date2 - date1;
    tmp = Math.floor(tmp / 1000);
    diff.sec = tmp % 60;
    tmp = Math.floor((tmp - diff.sec) / 60);
    diff.min = tmp % 60;
    tmp = Math.floor((tmp - diff.min) / 60);
    diff.hour = tmp % 24;
    return Number("".concat(diff.hour * 60 + diff.min));
  }

  var blur = function blur(input) {
    var hours = input.parentNode.querySelector('.hours').value;
    var minutes = input.parentNode.querySelector('.minutes').value;

    if (hours.length > 0 && minutes.length > 0) {
      var key = input.closest('form').dataset.type;
      var msg = "";

      for (var bus in datas) {
        var match = false;

        var _iterator = _createForOfIteratorHelper(datas[bus][key]),
            _step;

        try {
          for (_iterator.s(); !(_step = _iterator.n()).done;) {
            var time = _step.value;

            if (!match) {
              var diff = dateDiff1("".concat(hours, ":").concat(minutes), time['depart']);
              var correspondance = dateDiff("".concat(hours, ":").concat(minutes), time['depart']);
              var classe = 'not';

              if (diff <= 80 && diff > 40) {
                match = true;
                classe = 'large';
              }

              if (diff <= 40 && diff > 20) {
                match = true;
                classe = 'valid';
              } else if (diff <= 20 && diff > 15) {
                match = true;
                classe = 'risque';
              } else if (diff <= 15 && diff > 5) {
                match = true;
              }

              if (match) {
                msg += "<li class=\"".concat(classe, "\"><b>").concat(bus, "</b> (").concat(datas[bus]['name'], ") ").concat(time['depart'], " - ").concat(time['arriver'], " (").concat(correspondance, " de correspondance)</li>");
              }
            }
          }
        } catch (err) {
          _iterator.e(err);
        } finally {
          _iterator.f();
        }
      }

      input.parentNode.parentNode.querySelector('ul').innerHTML = msg ? msg : '<li>--</li>';
    }
  };

  var add = function add(inputs) {
    inputs.forEach(function (input) {
      blur(input);

      input.onkeyup = function (e) {
        var charCode = e.keyCode;

        if (input.nextElementSibling && charCode != 9 && charCode != 16) {
          if (input.value.length == 1 && charCode >= 99 && charCode <= 105) {
            input.nextElementSibling.focus();
          }

          if (input.value.length == 2) {
            input.nextElementSibling.focus();
          }
        } // just numeric


        if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
      };

      input.onblur = function (e) {
        var charCode = e.keyCode;
        /*  if (input.value.length == 0 && input.classList.contains('minutes') && charCode != 98) {
            input.value = '00';
        }
        if(input.value === '0' && input.classList.contains('minutes') && charCode != 98){
            input.value = '00';
        }*/

        blur(input);
      };
    });
  };

  btns_add.forEach(function (btn) {
    btn.onclick = function () {
      var ol = btn.previousElementSibling;
      var template = document.querySelector("#time");
      var clone = document.importNode(template.content, true);
      ol.appendChild(clone);
      var inputs = ol.querySelectorAll('li:last-child input');
      add(inputs);
      inputs[inputs.length - 2].focus();
    };
  });
  btns_reset.forEach(function (btn) {
    btn.onclick = function () {
      var form = btn.closest('form');
      form.querySelectorAll('input').forEach(function (input) {
        input.value = '';
      });
      form.querySelectorAll('ul').forEach(function (ul) {
        ul.innerHTML = '';
      });
    };
  });
  add(inputs);
});

/***/ })

}]);
//# sourceMappingURL=2e16221460a8c509b8cb.js.map