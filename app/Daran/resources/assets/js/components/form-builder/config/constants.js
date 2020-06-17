import {faEdit} from '@fortawesome/free-solid-svg-icons/faEdit';
import {faCalculator} from '@fortawesome/free-solid-svg-icons/faCalculator';
import {faCalendarAlt} from '@fortawesome/free-solid-svg-icons/faCalendarAlt';
import {faClock} from '@fortawesome/free-solid-svg-icons/faClock';
import {faCheck} from '@fortawesome/free-solid-svg-icons/faCheck';
import {faDatabase} from '@fortawesome/free-solid-svg-icons/faDatabase';

var FORM_CONSTANTS = {};
var CONTROL_CONSTANTS = {};

FORM_CONSTANTS.SectionLayout = {
  collapse: "Collapse",
  tab: "Tab",
  fixed: "Standard",
  // inner: "Inner Parent",
};

FORM_CONSTANTS.Section = {
    name: "",
    label: "",
    clientKey: "",
    order: 0,
    rows: [],

    // config
    labelPosition: "left", // left or top

    // for dynamic
    isDynamic: false,
    minInstance: 1,
    maxInstance: 0, //0 for unlimited
    instances: [], // for save data in GUI to easily to retrieve @@
};

FORM_CONSTANTS.Row = {
    name: "",
    label: "",
    order: 0,
    controls: [],
};

FORM_CONSTANTS.Control = {
    type: "",
    name: "",
    fieldName: "",
    label: "",
    order: 0,
    defaultValue: "",
    value: "",
    className: 'col-md-4',
    readonly: false,

    // label style
    labelBold: false,
    labelItalic: false,
    labelUnderline: false,

    // validation
    required: false,

    // attr for text
    isMultiLine: false,

    // attr for number
    isInteger: false,
    decimalPlace: 0,

    // attr for datePicker
    isTodayValue: false,
    dateFormat: "dd/mm/yyyy",

    // attr for timePicker
    isNowTimeValue: false,
    timeFormat: "HH:mm", // enhancement later

    // attr for select
    isMultiple: false,
    isRadio: false,
    isAjax: false, // is ajax data source or not
    dataOptions: [], // static data source
    ajaxDataUrl: "", // ajax data source

    // attr for checkbox
    isChecked: false,

    image: ""
};

FORM_CONSTANTS.Type = {
    text: {
        label:"Input Testo",
        icon: faEdit
    },
    number: {
        label:"Input Numerico",
        icon: faCalculator
    },
    datepicker: {
        label: "Selettore Data",
        icon: faCalendarAlt
    },
    timepicker: {
        label:"Selettore Ora",
        icon: faClock
    },
    select: {
        label: "Selezione",
        icon: faDatabase
    },
    checkbox: {
        label:"Checkbox",
        icon: faCheck
    },
};

FORM_CONSTANTS.WidthOptions = {
    "col-1": "Larghezza 1 parti",
    "col-2": "Larghezza 2 parti",
    "col-3": "Larghezza 3 parti",
    "col-4": "Larghezza 4 parti",
    "col-5": "Larghezza 5 parti",
    "col-6": "Larghezza 6 parti",
    "col-7": "Larghezza 7 parti",
    "col-8": "Larghezza 8 parti",
    "col-9": "Larghezza 9 parti",
    "col-10": "Larghezza 10 parti",
    "col-11": "Larghezza 11 parti",
    "col-12": "Larghezza 12 parti",
};

FORM_CONSTANTS.OptionDefault = {
    id: "",
    text: ""
};

CONTROL_CONSTANTS.DateFormat = {
    // rule: date picker format => moment format
    'dd/mm/yy': "D/M/YYYY",
    'dd-mm-yy': "D-M-YYYY",
    'mm/dd/yy': "M/D/YYYY",
    'mm-dd-yy': "M/D/YYYY",
    'yy/mm/dd': "YYYY/M/D",
    'yy-mm-dd': "YYYY-M-D",
};

CONTROL_CONSTANTS.TimeFormat = {
    'H:m': 'H:m',
    'HH:mm': 'HH:mm',
    'h:m p': "h:m A",
    'hh:mm p': "hh:mm A"
};

export {
    FORM_CONSTANTS,
    CONTROL_CONSTANTS
}
