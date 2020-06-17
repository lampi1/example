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
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./app/Daran/resources/assets/css/app.scss":
/*!*************************************************!*\
  !*** ./app/Daran/resources/assets/css/app.scss ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./app/Daran/resources/assets/js/app.js":
/*!**********************************************!*\
  !*** ./app/Daran/resources/assets/js/app.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

/******/
(function (modules) {
  // webpackBootstrap

  /******/
  // The module cache

  /******/
  var installedModules = {};
  /******/

  /******/
  // The require function

  /******/

  function __webpack_require__(moduleId) {
    /******/

    /******/
    // Check if module is in cache

    /******/
    if (installedModules[moduleId]) {
      /******/
      return installedModules[moduleId].exports;
      /******/
    }
    /******/
    // Create a new module (and put it into the cache)

    /******/


    var module = installedModules[moduleId] = {
      /******/
      i: moduleId,

      /******/
      l: false,

      /******/
      exports: {}
      /******/

    };
    /******/

    /******/
    // Execute the module function

    /******/

    modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
    /******/

    /******/
    // Flag the module as loaded

    /******/

    module.l = true;
    /******/

    /******/
    // Return the exports of the module

    /******/

    return module.exports;
    /******/
  }
  /******/

  /******/

  /******/
  // expose the modules object (__webpack_modules__)

  /******/


  __webpack_require__.m = modules;
  /******/

  /******/
  // expose the module cache

  /******/

  __webpack_require__.c = installedModules;
  /******/

  /******/
  // define getter function for harmony exports

  /******/

  __webpack_require__.d = function (exports, name, getter) {
    /******/
    if (!__webpack_require__.o(exports, name)) {
      /******/
      Object.defineProperty(exports, name, {
        enumerable: true,
        get: getter
      });
      /******/
    }
    /******/

  };
  /******/

  /******/
  // define __esModule on exports

  /******/


  __webpack_require__.r = function (exports) {
    /******/
    if (typeof Symbol !== 'undefined' && Symbol.toStringTag) {
      /******/
      Object.defineProperty(exports, Symbol.toStringTag, {
        value: 'Module'
      });
      /******/
    }
    /******/


    Object.defineProperty(exports, '__esModule', {
      value: true
    });
    /******/
  };
  /******/

  /******/
  // create a fake namespace object

  /******/
  // mode & 1: value is a module id, require it

  /******/
  // mode & 2: merge all properties of value into the ns

  /******/
  // mode & 4: return value when already ns object

  /******/
  // mode & 8|1: behave like require

  /******/


  __webpack_require__.t = function (value, mode) {
    /******/
    if (mode & 1) value = __webpack_require__(value);
    /******/

    if (mode & 8) return value;
    /******/

    if (mode & 4 && _typeof(value) === 'object' && value && value.__esModule) return value;
    /******/

    var ns = Object.create(null);
    /******/

    __webpack_require__.r(ns);
    /******/


    Object.defineProperty(ns, 'default', {
      enumerable: true,
      value: value
    });
    /******/

    if (mode & 2 && typeof value != 'string') for (var key in value) {
      __webpack_require__.d(ns, key, function (key) {
        return value[key];
      }.bind(null, key));
    }
    /******/

    return ns;
    /******/
  };
  /******/

  /******/
  // getDefaultExport function for compatibility with non-harmony modules

  /******/


  __webpack_require__.n = function (module) {
    /******/
    var getter = module && module.__esModule ?
    /******/
    function getDefault() {
      return module['default'];
    } :
    /******/
    function getModuleExports() {
      return module;
    };
    /******/

    __webpack_require__.d(getter, 'a', getter);
    /******/


    return getter;
    /******/
  };
  /******/

  /******/
  // Object.prototype.hasOwnProperty.call

  /******/


  __webpack_require__.o = function (object, property) {
    return Object.prototype.hasOwnProperty.call(object, property);
  };
  /******/

  /******/
  // __webpack_public_path__

  /******/


  __webpack_require__.p = "/";
  /******/

  /******/

  /******/
  // Load entry module and return exports

  /******/

  return __webpack_require__(__webpack_require__.s = 0);
  /******/
})(
/************************************************************************/

/******/
{
  /***/
  "./resources/js/app.js":
  /*!*****************************!*\
    !*** ./resources/js/app.js ***!
    \*****************************/

  /*! no static exports found */

  /***/
  function resourcesJsAppJs(module, exports) {
    /* input type file */
    //     // Register any plugins
    //     FilePond.registerPlugin(FilePondPluginImageExifOrientation);
    //     FilePond.registerPlugin(FilePondPluginImagePreview);
    //     FilePond.registerPlugin(FilePondPluginFileEncode);
    //
    //     // Create FilePond object
    //     const inputElement = document.querySelector('input.filepond');
    //     const pond = FilePond.create(inputElement);
    //
    //
    // pond.setOptions({
    //     labelIdle:'Trascina il file da caricare - <span class="filepond--label-action"> SELEZIONA</span>',
    //     allowFileEncode: false
    //
    // });

    /* tooltips */
    tippy('[data-tooltip="tooltip"]', {
      content: function content(reference) {
        var title = reference.getAttribute('title');
        reference.removeAttribute('title');
        return title;
      },
      arrow: true
    });
    /* select & select multiple */

    $(".select2-multiple").select2({
      multiple: true
    });
    $(".select2").select2();
    /* datetimepicker */

    $('.form_date').datetimepicker({
      language: 'it',
      weekStart: 1,
      todayBtn: 1,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      minView: 2,
      forceParse: 0,
      format: 'dd/mm/yyyy'
    });
    /***/
  },

  /***/
  "./resources/sass/app.scss":
  /*!*********************************!*\
    !*** ./resources/sass/app.scss ***!
    \*********************************/

  /*! no static exports found */

  /***/
  function resourcesSassAppScss(module, exports) {// removed by extract-text-webpack-plugin

    /***/
  },

  /***/
  "./resources/sass/bootstrap/bootstrap.scss":
  /*!*************************************************!*\
    !*** ./resources/sass/bootstrap/bootstrap.scss ***!
    \*************************************************/

  /*! no static exports found */

  /***/
  function resourcesSassBootstrapBootstrapScss(module, exports) {// removed by extract-text-webpack-plugin

    /***/
  },

  /***/
  "./resources/sass/select2/core.scss":
  /*!******************************************!*\
    !*** ./resources/sass/select2/core.scss ***!
    \******************************************/

  /*! no static exports found */

  /***/
  function resourcesSassSelect2CoreScss(module, exports) {// removed by extract-text-webpack-plugin

    /***/
  },

  /***/
  0:
  /*!******************************************************************************************************************************************!*\
    !*** multi ./resources/js/app.js ./resources/sass/app.scss ./resources/sass/bootstrap/bootstrap.scss ./resources/sass/select2/core.scss ***!
    \******************************************************************************************************************************************/

  /*! no static exports found */

  /***/
  function _(module, exports, __webpack_require__) {
    __webpack_require__(
    /*! /Users/andrea/Documents/Progetti/palestra/resources/js/app.js */
    "./resources/js/app.js");

    __webpack_require__(
    /*! /Users/andrea/Documents/Progetti/palestra/resources/sass/app.scss */
    "./resources/sass/app.scss");

    __webpack_require__(
    /*! /Users/andrea/Documents/Progetti/palestra/resources/sass/bootstrap/bootstrap.scss */
    "./resources/sass/bootstrap/bootstrap.scss");

    module.exports = __webpack_require__(
    /*! /Users/andrea/Documents/Progetti/palestra/resources/sass/select2/core.scss */
    "./resources/sass/select2/core.scss");
    /***/
  }
  /******/

});

/***/ }),

/***/ 0:
/*!**********************************************************************************************!*\
  !*** multi ./app/Daran/resources/assets/js/app.js ./app/Daran/resources/assets/css/app.scss ***!
  \**********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/francesco/Documents/Progetti/franzini/app/Daran/resources/assets/js/app.js */"./app/Daran/resources/assets/js/app.js");
module.exports = __webpack_require__(/*! /Users/francesco/Documents/Progetti/franzini/app/Daran/resources/assets/css/app.scss */"./app/Daran/resources/assets/css/app.scss");


/***/ })

/******/ });
