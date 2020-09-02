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

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports) {

// gsap.registerPlugin(ScrollToPlugin);

/* scrollo all'ID nel data-scroll-to (x HOMEPAGE) */
var scrollsTo = document.querySelectorAll('[data-scroll-to]');
var menuHeight = 100;
scrollsTo.forEach(function (el, i) {
  var dataScr = el.getAttribute('data-scroll-to');
  scrollTo = '#' + dataScr; // console.log(scrollTo);

  var find = document.getElementById(dataScr); // console.log(find);

  console.log('click');

  if (scrollTo && find) {
    el.addEventListener('click', function (e) {
      // console.log('click');
      // console.log(scrollTo);
      // console.log(find);
      e.preventDefault(); // gsap.to(window, {duration: .75, to: {y:find
      //     // , offsetY:menuHeight
      // }});

      $('html,body').animate({
        scrollTop: $(find).offset().top - 100
      }, '2000');
    });
  }
}); //select2

$('.select2').select2({
  minimumResultsForSearch: -1
});
var controller = new ScrollMagic.Controller(); //menu

var menu = document.getElementById('js--menubar'); // let hamburger = document.getElementById('js--hamburger');

var logo = document.getElementById('logo-lottie'); // let hbLine1 = hamburger.getElementsByTagName('line')[0];
// let hbLine2 = hamburger.getElementsByTagName('line')[1];
// let hbLine3 = hamburger.getElementsByTagName('line')[2];
// console.log(hbLine3);

var tl = new TimelineMax({
  paused: true
});
tl.addLabel('start'); // tl.to(hbLine1, 0.01, {stroke:'#ffffff'}, 'start');
// tl.to(hbLine2, 0.01, {stroke:'#ffffff'}, 'start');
// tl.to(hbLine3, 0.01, {stroke:'#ffffff'}, 'start');

tl.to(menu, 0.01, {
  position: 'fixed',
  top: '-100px'
}, 'start'); // tl.to(hamburger, 0.01, {position:'fixed', top:'-100px'}, 'start');

tl.to(logo, 0.01, {
  position: 'fixed'
}, 'start');
tl.addLabel('middle');
tl.to(menu, 0.3, {
  top: '0px'
}, ' middle'); // tl.to(hamburger, 0.3, {top:'25px'}, 'middle');

menu.timeline = tl; // script animate menu

var menuTrigger = document.getElementById('js--show-menu');
var menuAnimPlayed = false;
var scene = new ScrollMagic.Scene({
  triggerElement: menuTrigger,
  // offset: -120
  triggerHook: 0,
  reverse: true
}).on('start', function () {
  // console.log('play');
  menu.timeline.play();
}).on('leave', function () {
  // console.log('reverse');
  menu.timeline.reverse();
}) // .addIndicators({
//     colorTrigger: "red",
//     colorStart: "black",
//     colorEnd: "black",
//     indent: 10
// })
.addTo(controller); //script generico animate

window.startSMAnimation = function () {
  var animatedItems = $('[data-anim]');
  $(animatedItems).each(function (i, e) {
    var delay = 0; // console.log(e);
    // if ($(e).data('anim-delay')) {
    //   console.log('delay si');
    // }
    // if ($(e).data('anim-durarion')) {
    //   console.log('duration si');
    // }

    var played = false;
    var scene = new ScrollMagic.Scene({
      triggerElement: e,
      // offset: -120
      triggerHook: 0.75
    }).on('start', function () {
      if (played == false) {
        // console.log('animation started');
        // console.log(e.timeline);
        e.timeline.play();
      }

      played = true;
    }) // .addIndicators({
    //     colorTrigger: "red",
    //     colorStart: "black",
    //     colorEnd: "black",
    //     indent: 10
    // })
    .addTo(controller);
  });
};

document.addEventListener('DOMContentLoaded', function () {
  startSMAnimation();
}); //animation x hover menu box

function injectAnimations() {
  $('[data-anim]').each(function (i, e) {
    var delay = 0;
    var duration = 0.5; // TODO: rimuovere data anim e duration x questo progetto, gestirli direttamente in tl

    if ($(e).data('anim-delay')) {
      delay = $(e).data('anim-delay');
    } //DURATION


    if ($(e).data('anim-duration')) {
      duration = $(e).data('anim-duration');
    } //ANIMATION


    var animationType = $(e).data('anim');
    console.log(animationType);
    var tl = new TimelineMax({
      paused: true
    });
    tl.defaultEase = Expo.easeInOut; //tl

    switch (animationType) {
      case 'fadeInUp':
        tl.fromTo(e, duration, {
          delay: delay,
          y: 10,
          autoAlpha: 0
        }, {
          y: 0,
          autoAlpha: 1
        });
        break;
      // case 'fadeInLeft':
      //   tl.fromTo(e, duration, {delay: delay, xPercent: -100, ease:Expo.easeInOut},{xPercent: 0, ease:Expo.easeInOut});
      //   break;
      // case 'fadeInRight':
      //   tl.fromTo(e, duration, {delay: delay, xPercent: 100, ease:Expo.easeInOut},{xPercent: 0, ease:Expo.easeInOut});
      //   break;
      // case 'fadeInUp':
      //   tl.fromTo(e, duration, {delay: delay, yPercent: 100, ease:Expo.easeInOut},{yPercent: 0, ease:Expo.easeInOut});
      //   break;
      // case 'fadeInDown':
      //   tl.fromTo(e, duration, {delay: delay, yPercent: -100, ease:Expo.easeInOut},{yPercent: 0, ease:Expo.easeInOut});
      //   break;
    }

    e.timeline = tl;
  });
}

injectAnimations(); //news box preview
// function animateNewsBox(elements){
//     $(elements).each(function(i,e){
//         let tl = new TimelineMax({paused: true});
//         tl.addLabel("start")
//         .fromTo(e, .75, {translateY: '-5vh', autoAlpha:0, ease:Expo.easeInOut}, {translateY: '0vh', autoAlpha:1, ease:Expo.easeInOut})
//         e.timeline = tl;
//     });
// };
// let newsBoxes = $('.js--box-news');
// animateNewsBox(newsBoxes);

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!*************************************************************!*\
  !*** multi ./resources/js/app.js ./resources/sass/app.scss ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/Lampione/progetti/global-job-service/resources/js/app.js */"./resources/js/app.js");
module.exports = __webpack_require__(/*! /Users/Lampione/progetti/global-job-service/resources/sass/app.scss */"./resources/sass/app.scss");


/***/ })

/******/ });