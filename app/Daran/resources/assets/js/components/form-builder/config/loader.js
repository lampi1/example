import underscoreDeepExtend from 'underscore-deep-extend';
import moment from 'moment';
import {FontAwesomeRegister} from "./font-awesome-register";

// load jquery
if (!window.$) {
    let $ = require('jquery');
    window.$ = $;
    window.jQuery = $;
}

// load jquery ui
import 'webpack-jquery-ui';
import 'webpack-jquery-ui/css';

// load timepicker
require('../assets/js/jquery.timepicker.min');
require('../assets/css/jquery.timepicker.min.css');
require('../assets/css/v-form.css');

// load bootstrap
// import 'bootstrap/dist/css/bootstrap.min.css';
// require('scriptjs');

// load font-awesome
FontAwesomeRegister.register();

// load underscore
window._ = require('underscore'); // override
require('./helper_function');
_.mixin({deepExtend: underscoreDeepExtend(_)});

// // load moment
if (!window.moment) {
    window.moment = moment;
}

// toaster
require('../assets/js/jquery.noty.packaged');

// validateJS
require('../assets/js/validate.min.js');
