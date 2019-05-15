/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('bootstrap');
window.$ = window.jQuery = require('jquery');

$(document).ready(function() {
    $('.cart').click(function() {
        $('.shopping-cart').toggleClass('d-none');
    });
});
