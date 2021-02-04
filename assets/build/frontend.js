/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/src/frontend/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/frontend/index.js":
/*!**************************************!*\
  !*** ./assets/src/frontend/index.js ***!
  \**************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__);

 // Initialise

var sayWhat = {
  // Filter callbacks for each translation call. Uses handle() to do the heavy lifting.
  gettext: function gettext(translation, text, domain) {
    return sayWhat.handle(translation, text, text, undefined, undefined, domain);
  },
  gettext_with_context: function gettext_with_context(translation, text, context, domain) {
    return sayWhat.handle(translation, text, text, undefined, context, domain);
  },
  ngettext: function ngettext(translation, single, plural, number, domain) {
    return sayWhat.handle(translation, single, plural, number, undefined, domain);
  },
  ngettext_with_context: function ngettext_with_context(translation, single, plural, number, context, domain) {
    return sayWhat.handle(translation, single, plural, number, context, domain);
  },
  has_translation: function has_translation(result, single, context, domain) {
    return sayWhat.handle(single, single, single, undefined, context, domain) !== single;
  },

  /**
   * Handle a call to a translation function.
   *
   * Looks for a replacement and filters the return value if required. If String Discovery is active
   * also check whether this is a known available string or not, and queues discovery if not.
   *
   * @param {string} translation        The translated string.
   * @param {string} single             Text to translate if non-plural.
   * @param {string} plural             Text to translate if plural.
   * @param {string|undefined} number   The number used to decide on plural/non-plural.
   * @param {string|undefined} context  Context information for the translators.
   * @param {string} domain             Domain to retrieve the translated text.
   */
  handle: function handle(translation, single, plural, number, context, domain) {
    // Adjust inputs to expected format.
    if (typeof domain === 'undefined') {
      domain = '';
    }

    if (typeof context === 'undefined') {
      context = '';
    } // Assume we're using plural for now.


    var compositeKey = domain + '|' + plural + '|' + context; // Revert to the single form if required.

    if (number === undefined || number === 1) {
      compositeKey = domain + '|' + single + '|' + context;
    }
    /**
     * Look for replacements, and use them if configured.
     */


    if (lodash__WEBPACK_IMPORTED_MODULE_0__["has"](window.say_what_data.replacements, compositeKey)) {
      return window.say_what_data.replacements[compositeKey];
    } // No replacement. Return the value unchanged.


    return translation;
  }
};
/**
 * Attach filters.
 */

wp.hooks.addFilter('i18n.gettext', 'say-what', sayWhat.gettext, 99);
wp.hooks.addFilter('i18n.ngettext', 'say-what', sayWhat.ngettext, 99);
wp.hooks.addFilter('i18n.gettext_with_context', 'say-what', sayWhat.gettext_with_context, 99);
wp.hooks.addFilter('i18n.ngettext_with_context', 'say-what', sayWhat.ngettext_with_context, 99);
wp.hooks.addFilter('i18n.has_translation', 'say-what', sayWhat.has_translation, 99);

/***/ }),

/***/ "@wordpress/hooks":
/*!*******************************!*\
  !*** external ["wp","hooks"] ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["hooks"]; }());

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["lodash"]; }());

/***/ })

/******/ });
//# sourceMappingURL=frontend.js.map