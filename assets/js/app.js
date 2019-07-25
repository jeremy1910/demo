/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.scss in this case)
require('../css/app.scss');
require('../css/custom.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
//const $ = require('jquery');


// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
const $ = require('jquery');

global.$ = global.jQuery = $;

require('bootstrap');
require('bootstrap-select');
require('../../public/library/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min');


// or you can include specific pieces
require('bootstrap/js/dist/tooltip');
require('bootstrap/js/dist/popover');

//global.$ = global.jQuery = $;


