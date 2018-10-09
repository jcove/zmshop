'use strict';

var _vueI18n = require('vue-i18n');

var _vueI18n2 = _interopRequireDefault(_vueI18n);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.use(_vueI18n2.default);

var i18n = new _vueI18n2.default({ locale: localStorage.getItem('lang') || 'cn', // 语言标识
    messages: {
        'cn': require('./lang/cn'), // 中文语言包
        'en': require('./lang/en'), // 英文语言包
        'jp': require('./lang/jp')
    } });

var app = new Vue({
    el: '#app',
    i18n: i18n
});

require('./functions');