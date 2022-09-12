"use strict";
(self["webpackChunkwordpress_starter_kit"] = self["webpackChunkwordpress_starter_kit"] || []).push([["assets_js_strates_contact_js"],{

/***/ "./assets/js/modules/formValidate.js":
/*!*******************************************!*\
  !*** ./assets/js/modules/formValidate.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

/* eslint-disable */

/**
 * formValidate
 */
var FormValidate = function FormValidate(form, onSend) {
  var _this = this;

  var fields = form.querySelectorAll(':required');
  var mandatory = form.getAttribute('data-mandatory');
  var validity = true;
  var init = true;

  this.reset = function () {
    init = true;

    var _iterator = _createForOfIteratorHelper(fields),
        _step;

    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var field = _step.value;
        field.removeAttribute('aria-invalid');
        field.parentNode.querySelector('.invalid-msg').remove();
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
  };

  var validate = function validate() {
    if (init) return;
    validity = true;

    var _iterator2 = _createForOfIteratorHelper(fields),
        _step2;

    try {
      for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
        var field = _step2.value;
        var dataTypeMismatch = field.dataset.typemismatch;
        var dataPatternMismatch = field.dataset.patternmismatch;
        var typeMismatch = field.validity.typeMismatch;
        var tooShort = field.validity.tooShort;
        var tooLong = field.validity.tooLong;
        var stepMismatch = field.validity.stepMismatch;
        var patternMismatch = field.validity.patternMismatch;
        var valueMissing = field.validity.valueMissing;
        var group = field.closest('[role="group"]');
        var invalid_msg = group ? group.querySelector('.invalid-msg') : field.parentNode.querySelector('.invalid-msg');

        if (!invalid_msg) {
          invalid_msg = document.createElement('div');
          invalid_msg.className = 'invalid-msg';
          invalid_msg.id = field.getAttribute('aria-describedby').split(' ')[0];

          if (group) {
            group.insertAdjacentElement('beforeend', invalid_msg);
          } else {
            field.insertAdjacentElement('afterend', invalid_msg);
          }
        }

        if (!field.checkValidity()) {
          field.setAttribute('aria-invalid', true);
          var msg = '';

          if ((typeMismatch || stepMismatch || tooShort || tooLong) && dataTypeMismatch) {
            msg = dataTypeMismatch;
          }

          if (patternMismatch && dataPatternMismatch) {
            msg = dataPatternMismatch;
          }

          if (valueMissing && mandatory) {
            msg = mandatory;
          }

          field.setCustomValidity(msg);
          invalid_msg.innerHTML = field.validationMessage;
          validity = false;
        } else {
          field.removeAttribute('aria-invalid');
          invalid_msg.innerHTML = '';
        }
      }
    } catch (err) {
      _iterator2.e(err);
    } finally {
      _iterator2.f();
    }

    return validity;
  };

  var _iterator3 = _createForOfIteratorHelper(fields),
      _step3;

  try {
    for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
      var field = _step3.value;
      field.addEventListener('input', function () {
        return validate();
      });
      field.addEventListener('change', function () {
        return validate();
      });
    }
  } catch (err) {
    _iterator3.e(err);
  } finally {
    _iterator3.f();
  }

  form.onsubmit = function (e) {
    e.preventDefault();
    init = false;
    validate() && onSend(_this);
  };
};

/* harmony default export */ __webpack_exports__["default"] = (FormValidate);

/***/ }),

/***/ "./assets/js/strates/contact.js":
/*!**************************************!*\
  !*** ./assets/js/strates/contact.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modules_formValidate_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../modules/formValidate.js */ "./assets/js/modules/formValidate.js");
/* eslint-disable */

/* harmony default export */ __webpack_exports__["default"] = (function (el) {
  var form = el.querySelector('form');

  if (form) {
    new _modules_formValidate_js__WEBPACK_IMPORTED_MODULE_0__["default"](form, function () {
      form.submit();
    });
  }
});

/***/ })

}]);
//# sourceMappingURL=e4f5556d2ef581bb733f.js.map