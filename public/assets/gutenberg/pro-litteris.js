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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/scripts/gutenberg.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayLikeToArray.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) {
    arr2[i] = arr[i];
  }

  return arr2;
}

module.exports = _arrayLikeToArray;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayWithHoles.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayWithHoles.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _arrayWithHoles(arr) {
  if (Array.isArray(arr)) return arr;
}

module.exports = _arrayWithHoles;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/defineProperty.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/defineProperty.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

module.exports = _defineProperty;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js ***!
  \*********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _iterableToArrayLimit(arr, i) {
  if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return;
  var _arr = [];
  var _n = true;
  var _d = false;
  var _e = undefined;

  try {
    for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) {
      _arr.push(_s.value);

      if (i && _arr.length === i) break;
    }
  } catch (err) {
    _d = true;
    _e = err;
  } finally {
    try {
      if (!_n && _i["return"] != null) _i["return"]();
    } finally {
      if (_d) throw _e;
    }
  }

  return _arr;
}

module.exports = _iterableToArrayLimit;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableRest.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableRest.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _nonIterableRest() {
  throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

module.exports = _nonIterableRest;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/slicedToArray.js":
/*!**************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/slicedToArray.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayWithHoles = __webpack_require__(/*! ./arrayWithHoles */ "./node_modules/@babel/runtime/helpers/arrayWithHoles.js");

var iterableToArrayLimit = __webpack_require__(/*! ./iterableToArrayLimit */ "./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js");

var unsupportedIterableToArray = __webpack_require__(/*! ./unsupportedIterableToArray */ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js");

var nonIterableRest = __webpack_require__(/*! ./nonIterableRest */ "./node_modules/@babel/runtime/helpers/nonIterableRest.js");

function _slicedToArray(arr, i) {
  return arrayWithHoles(arr) || iterableToArrayLimit(arr, i) || unsupportedIterableToArray(arr, i) || nonIterableRest();
}

module.exports = _slicedToArray;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) {
  "@babel/helpers - typeof";

  if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
    module.exports = _typeof = function _typeof(obj) {
      return typeof obj;
    };
  } else {
    module.exports = _typeof = function _typeof(obj) {
      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };
  }

  return _typeof(obj);
}

module.exports = _typeof;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js":
/*!***************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray */ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js");

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return arrayLikeToArray(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return arrayLikeToArray(o, minLen);
}

module.exports = _unsupportedIterableToArray;

/***/ }),

/***/ "./src/scripts/container/Plugin.js":
/*!*****************************************!*\
  !*** ./src/scripts/container/Plugin.js ***!
  \*****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/typeof.js");
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _hooks_use_pro_litteris_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../hooks/use-pro-litteris.js */ "./src/scripts/hooks/use-pro-litteris.js");
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../utils.js */ "./src/scripts/utils.js");







var Pixel = function Pixel(_ref) {
  var _ref$pixel = _ref.pixel,
      pixel = _ref$pixel === void 0 ? {} : _ref$pixel;
  var url = pixel.url;
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__["TextControl"], {
    label: "Pixel",
    value: url,
    readOnly: true
  });
};

var Message = function Message(_ref2) {
  var _ref2$message = _ref2.message,
      message = _ref2$message === void 0 ? {} : _ref2$message,
      _ref2$draft = _ref2.draft,
      draft = _ref2$draft === void 0 ? {} : _ref2$draft,
      onSubmitReport = _ref2.onSubmitReport;
  var isDirtyState = Object(_hooks_use_pro_litteris_js__WEBPACK_IMPORTED_MODULE_4__["useIsPostDirtyState"])();
  var isSaving = Object(_hooks_use_pro_litteris_js__WEBPACK_IMPORTED_MODULE_4__["useIsSavingPost"])();

  if (_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1___default()(draft.pixelUid) === ( true ? "undefined" : undefined) && _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1___default()(message.pixelUid) === ( true ? "undefined" : undefined)) {
    return null;
  }

  var isReported = _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1___default()(message.reported) !== ( true ? "undefined" : undefined);

  var _ref3 = message.pixelUid ? message : draft,
      pixelUid = _ref3.pixelUid,
      title = _ref3.title,
      plaintext = _ref3.plaintext,
      participants = _ref3.participants;

  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])("h3", null, "Meldung"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__["TextControl"], {
    label: "UID",
    value: pixelUid,
    readOnly: true
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__["TextControl"], {
    label: "Titel",
    value: title,
    readOnly: true
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__["TextareaControl"], {
    label: "Text (".concat(plaintext.length, " Zeichen)"),
    value: plaintext,
    readOnly: true
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__["BaseControl"], {
    label: "Autoren"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])("ul", {
    style: {
      listStylePosition: 'inside',
      margin: 0,
      marginBottom: 20
    }
  }, participants.map(function (p) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])("li", {
      style: {
        background: '#efefef',
        borderRadius: 4,
        padding: "6px 10px",
        border: "1px solid #757575"
      },
      key: p.memberId
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(Participant, p));
  }))), isReported ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])("p", {
    className: "description"
  }, Object(_utils_js__WEBPACK_IMPORTED_MODULE_5__["dateFormat"])(parseInt(message.reported) * 1000), "."), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])("p", null, "Meldung war erfolgreich \uD83C\uDF89")) : Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__["Button"], {
    disabled: isDirtyState || isSaving || isReported,
    isPrimary: true,
    title: "Bitte speichern vor dem Melden.",
    onClick: onSubmitReport
  }, "Jetzt melden"));
};

var Participant = function Participant(_ref4) {
  var memberId = _ref4.memberId,
      firstName = _ref4.firstName,
      surName = _ref4.surName,
      internalIdentification = _ref4.internalIdentification;
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])("a", {
    href: "/wp-admin/user-edit.php?user_id=".concat(internalIdentification)
  }, firstName, " ", surName, " (", memberId, ")");
};

var Plugin = function Plugin() {
  var _useProLitteris = Object(_hooks_use_pro_litteris_js__WEBPACK_IMPORTED_MODULE_4__["useProLitteris"])(),
      _useProLitteris2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useProLitteris, 2),
      state = _useProLitteris2[0],
      submitMessage = _useProLitteris2[1];

  if (_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1___default()(state.info) === _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1___default()("")) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])("p", null, state.info);
  }

  if (_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1___default()(state.error) === _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_1___default()("")) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])("p", null, "Error: ", state.error);
  }

  var pixel = state.pixel;

  if (!pixel) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])("p", null, "No valid pixel found.");
  }

  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(Pixel, {
    pixel: pixel
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])("hr", null), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__["createElement"])(Message, {
    message: state.message,
    draft: state.messageDraft,
    onSubmitReport: function onSubmitReport() {
      submitMessage();
    }
  }));
};

/* harmony default export */ __webpack_exports__["default"] = (Plugin);

/***/ }),

/***/ "./src/scripts/gutenberg.js":
/*!**********************************!*\
  !*** ./src/scripts/gutenberg.js ***!
  \**********************************/
/*! exports provided: DOCUMENT_PANEL_PLUGIN_ID, SIDEBAR_PLUGIN_ID */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "DOCUMENT_PANEL_PLUGIN_ID", function() { return DOCUMENT_PANEL_PLUGIN_ID; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SIDEBAR_PLUGIN_ID", function() { return SIDEBAR_PLUGIN_ID; });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/plugins */ "@wordpress/plugins");
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/edit-post */ "@wordpress/edit-post");
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _container_Plugin_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./container/Plugin.js */ "./src/scripts/container/Plugin.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__);







var DOCUMENT_PANEL_PLUGIN_ID = "pro-litteris-document-panel"; // ------------------------------------------
// extend documents panel
// ------------------------------------------

setTimeout(function () {
  Object(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__["registerPlugin"])(DOCUMENT_PANEL_PLUGIN_ID, {
    render: function render() {
      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2__["PluginDocumentSettingPanel"], {
        title: "ProLitteris",
        icon: "media-text",
        priority: "low"
      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_container_Plugin_js__WEBPACK_IMPORTED_MODULE_3__["default"], null));
    }
  });
}, 300);
var SIDEBAR_PLUGIN_ID = "pro-litteris-sidebar";
Object(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__["registerPlugin"])(SIDEBAR_PLUGIN_ID, {
  render: function render() {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2__["PluginSidebarMoreMenuItem"], {
      target: SIDEBAR_PLUGIN_ID,
      icon: "media-text"
    }, "ProLitteris"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2__["PluginSidebar"], {
      name: SIDEBAR_PLUGIN_ID,
      icon: "media-text",
      title: "ProLitteris"
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__["PanelBody"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_container_Plugin_js__WEBPACK_IMPORTED_MODULE_3__["default"], null))));
  }
});

/***/ }),

/***/ "./src/scripts/hooks/use-pro-litteris.js":
/*!***********************************************!*\
  !*** ./src/scripts/hooks/use-pro-litteris.js ***!
  \***********************************************/
/*! exports provided: useIsPostDirtyState, useIsSavingPost, useProLitteris */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "useIsPostDirtyState", function() { return useIsPostDirtyState; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "useIsSavingPost", function() { return useIsSavingPost; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "useProLitteris", function() { return useProLitteris; });
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);


function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }


var useIsPostDirtyState = function useIsPostDirtyState() {
  return Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["useSelect"])(function (select) {
    return select('core/editor').isEditedPostDirty();
  });
};
var useIsSavingPost = function useIsSavingPost() {
  return Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["useSelect"])(function (select) {
    return select('core/editor').isSavingPost();
  });
};
var useProLitteris = function useProLitteris() {
  var state = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["useSelect"])(function (select) {
    return select('core/editor').getEditedPostAttribute('pro_litteris');
  });
  var dispatch = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["useDispatch"])('core/editor');
  return [state, function () {
    dispatch.editPost({
      pro_litteris: _objectSpread(_objectSpread({}, state), {}, {
        pushMessage: true
      })
    }).then(function (_) {
      dispatch.savePost();
    });
  }];
};

/***/ }),

/***/ "./src/scripts/utils.js":
/*!******************************!*\
  !*** ./src/scripts/utils.js ***!
  \******************************/
/*! exports provided: dateFormat */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "dateFormat", function() { return dateFormat; });
/* harmony import */ var _wordpress_date__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/date */ "@wordpress/date");
/* harmony import */ var _wordpress_date__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_date__WEBPACK_IMPORTED_MODULE_0__);

var dateFormat = function dateFormat(timestamp) {
  var settings = Object(_wordpress_date__WEBPACK_IMPORTED_MODULE_0__["__experimentalGetSettings"])();

  return Object(_wordpress_date__WEBPACK_IMPORTED_MODULE_0__["date"])(settings.formats.datetime, timestamp);
};

/***/ }),

/***/ "@wordpress/components":
/*!*********************************************!*\
  !*** external {"this":["wp","components"]} ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["components"]; }());

/***/ }),

/***/ "@wordpress/data":
/*!***************************************!*\
  !*** external {"this":["wp","data"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["data"]; }());

/***/ }),

/***/ "@wordpress/date":
/*!***************************************!*\
  !*** external {"this":["wp","date"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["date"]; }());

/***/ }),

/***/ "@wordpress/edit-post":
/*!*******************************************!*\
  !*** external {"this":["wp","editPost"]} ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["editPost"]; }());

/***/ }),

/***/ "@wordpress/element":
/*!******************************************!*\
  !*** external {"this":["wp","element"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["element"]; }());

/***/ }),

/***/ "@wordpress/plugins":
/*!******************************************!*\
  !*** external {"this":["wp","plugins"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["plugins"]; }());

/***/ })

/******/ });
//# sourceMappingURL=pro-litteris.js.map